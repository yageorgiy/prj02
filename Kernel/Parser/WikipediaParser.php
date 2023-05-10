<?php
namespace Kernel\Parser;

class WikipediaParser
{
    private string $apiMount;

    public function __construct()
    {
        $this->apiMount = "";
    }

    public function init(string $apiMount)
    {
        $this->apiMount = $apiMount;
    }


    public function getRandomPage(): string
    {
        // Get json file contents via Wikipedia API
        $contents = @file_get_contents($this->apiMount . "?" . http_build_query([
            "action" => "query",
            "format" => "json",
//            "list" => "random",
//            "rnlimit" => "1"
            "generator" => "random",
            "grnnamespace" => "0",
            "rvprop" => "content",
            "grnlimit" => "1",
        ]));

        // If request failed then empty page name
        if ($contents == false)
            return "";

        // If json is corrupted then empty page name
        $asJson = @json_decode($contents, true);
        if ($asJson == null || !isset($asJson["query"]) || !isset($asJson["query"]["pages"]))
            return "";

        $pageName = "";
        foreach ($asJson["query"]["pages"] as $v) {
            $pageName = $v["title"];
            break;
        }

        return $pageName;
    }


    /**
     * Get all pages references in array via Wikipedia API
     * TODO: fix some unresolvable links of specific pages
     * @param string $page
     * @return array
     */
    public function getReferences(string $page): array
    {
        // Get json file contents via Wikipedia API
        $contents = @file_get_contents($this->apiMount . "?" . http_build_query([
            "action" => "query",
            "titles" => $page,
            "prop" => "links",
            "pllimit" => "max",
            "format" => "json"
        ]));

        // If request failed then empty array
        if ($contents == false)
            return [];

        // If json is corrupted then empty array
        $asJson = @json_decode($contents, true);
        if ($asJson == null || !isset($asJson["query"]) || !isset($asJson["query"]["pages"]))
            return [];

        // If no query pages array keys found
        $anyKeys = array_keys($asJson["query"]["pages"]);
        if(count($anyKeys) < 1)
            return [];

        if (
            !isset($asJson["query"]["pages"][$anyKeys[0]]) ||
            !isset($asJson["query"]["pages"][$anyKeys[0]]["links"])
            )
            return [];

        // Return only titles
        return array_map(function(array $value) {
             return $value["title"];
        }, $asJson["query"]["pages"][$anyKeys[0]]["links"]);
    }

}