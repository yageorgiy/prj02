<?php


namespace Kernel;


class Templates
{
    /**
     * Templates constructor.
     */
    public function __construct()
    {
    }

    /**
     * Load specific template
     * @param string $templateName
     * @param array $params
     * @return string
     */
    private function load(string $templateName, array $params = []): string
    {
        // Try to read file without warnings
        $contents = @file_get_contents(__DIR__ . "/../templates/" . $templateName);

        if($contents == false)
            return "";

        foreach ($params as $paramName => $paramValue){
            $contents = str_replace("{{" . $paramName . "}}", $paramValue, $contents);
        }

        return $contents;
    }

    /**
     * Load home page template
     * @return string
     */
    public function home(): string
    {
        return $this->load("home.html");
    }

    /**
     * Load error page template
     * @param string $error
     * @return string
     */
    public function error(string $error): string
    {
        return $this->load("error.html", [
            "ERROR_DESCRIPTION" => $error
        ]);
    }


    public function gameIsRunning(): string
    {
        return $this->load("game_is_running.html");
    }

    public function anotherPlayerIsChoosing(
        string $startPage,
        string $endPage,
        string $curPage,
        string $countPages,
    ): string
    {
        return $this->load("another_player_is_choosing.html", [
            "START_PAGE" => $startPage,
            "END_PAGE" => $endPage,
            "CUR_PAGE" => $curPage,
            "PAGES_COUNT" => $countPages,
        ]);
    }

    public function waitForPlayers(): string
    {
        return $this->load("wait_for_players.html");
    }

    /**
     * Quiz page
     * @param string $startPage
     * @param string $endPage
     * @param string $curPage
     * @param string $countPages
     * @param array $urls
     * @return string
     */
    public function quiz(
        string $startPage,
        string $endPage,
        string $curPage,
        string $countPages,
        array $urls,
        string $sessionKey
    ): string
    {

        $asString = "<li><a href='/?page=$startPage&session_key=$sessionKey'><i>Вернуться в начало на страницу '$startPage'</i></a></li>";

        foreach ($urls as $url)
            $asString .= "<li><a href='/?page=$url&session_key=$sessionKey'>$url</a></li>";

        return $this->load("quiz.html", [
            "START_PAGE" => $startPage,
            "END_PAGE" => $endPage,
            "CUR_PAGE" => $curPage,
            "PAGES_COUNT" => $countPages,
            "URLS" => $asString,
        ]);
    }


    public function results(
        array $player1,
        array $player2,
        array $player3,
        array $player4,
        string $startPage,
        string $endPage,
        string $count
    ) {
        return $this->load("results.html", [
            "PLAYER1_NAME"  => $player1["username"],
            "PLAYER1_SCORE" => $player1["score"],
            "PLAYER1_PAGE"  => $player1["current_page"],

            "PLAYER2_NAME"  => $player2["username"],
            "PLAYER2_SCORE" => $player2["score"],
            "PLAYER2_PAGE"  => $player2["current_page"],

            "PLAYER3_NAME"  => $player3["username"],
            "PLAYER3_SCORE" => $player3["score"],
            "PLAYER3_PAGE"  => $player3["current_page"],

            "PLAYER4_NAME"  => $player4["username"],
            "PLAYER4_SCORE" => $player4["score"],
            "PLAYER4_PAGE"  => $player4["current_page"],

            "START_PAGE" => $startPage,
            "END_PAGE" => $endPage,
            "COUNT" => $count,
        ]);
    }

}