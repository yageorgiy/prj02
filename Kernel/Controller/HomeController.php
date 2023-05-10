<?php


namespace Kernel\controller;


use Kernel\Kernel;
use Kernel\Response\BadRequestResponse;
use Kernel\Request;
use Kernel\Response\ErrorResponse;
use Kernel\Response\JsonResponse;
use Kernel\Response\RedirectResponse;
use Kernel\Response\Response;
use Kernel\Templates;

class HomeController extends Controller
{

    /**
     * Process request at home page
     * TODO: refactor & optimize method
     * @param Request $request
     * @param Kernel $kernel
     * @return Response
     */
    public function processRequest(Request $request, Kernel $kernel): Response
    {

        $database = $kernel->getDatabaseManager();
        $wiki = $kernel->getWikipediaParser();
        $get = $request->getGetParams();
        $game = $database->getGame();
        $players = $database->getPlayers();

        if($request->isGET()) {

            // Greetings for unregistered users
            if (!array_key_exists("session_key", $get)) {
                if($game != null)
                    return new Response($kernel->getTemplates()->gameIsRunning(), 200);

                return new Response($kernel->getTemplates()->home(), 200);
            }

            $currentPage = $get["page"] ?? "";
            $sessionKey = $get["session_key"];
            $currentPlayer = $database->getPlayerBySessionKey($sessionKey);

            // If user is corrupted or not found
            if ($currentPlayer == null || $currentPlayer == [])
                return new BadRequestResponse();


            // Wait for players if not enough
            if (count($players) < 4)
                return new Response($kernel->getTemplates()->waitForPlayers(), 200);

            // Create game
            if ($game == null) {
                $this->generateGame($kernel);
                $game = $database->getGame();
            }


            // If created game is corrupted
            if ($game["page_end"] == "" || $game["page_start"] == ""){
                $database->resetGame();
                return new ErrorResponse("Ошибка создания игры. Перезагрузите страницу.");
            }

            // Set start page of a player
            if ($currentPlayer["current_page"] == "") {
                $database->submitMove($sessionKey, (int)$currentPlayer["score"], $game["page_start"]);
                $currentPlayer = $database->getPlayerBySessionKey($sessionKey);
            }

            // Submit a move
            if (
                $game["page_end"] != $currentPlayer["current_page"] &&
                $game["current_player_id"] == $currentPlayer["id"] &&
                $currentPage != "" &&
                $currentPage != $currentPlayer["current_page"] &&

                // Search whether player can make a move (including for the first page)
                // TODO: optimize
                (
                    in_array($currentPage, $wiki->getReferences($currentPlayer["current_page"])) ||
                    $currentPage == $game["page_start"]
                )
            ) {
                $database->submitMove($sessionKey, (int)$currentPlayer["score"] + 1, $currentPage);

                // get next available player
                $nextPlayer = $database->nextPlayer((int)$game["current_player_id"], $game["page_end"]);
                if($nextPlayer == null || $nextPlayer == [])
                    $database->switchPlayer((int)$game["current_player_id"]);
                else
                    $database->switchPlayer($nextPlayer["id"]);

                $currentPlayer = $database->getPlayerBySessionKey($sessionKey);
                $game = $database->getGame();
            }

            // If won
            if($game["page_end"] == $currentPlayer["current_page"]) {

                $p1 = $database->getPlayerByID(1);
                $p2 = $database->getPlayerByID(2);
                $p3 = $database->getPlayerByID(3);
                $p4 = $database->getPlayerByID(4);

                if(
                    $currentPlayer["current_page"] == $p1["current_page"] && // remove extra
                    $p1["current_page"] == $p2["current_page"] &&
                    $p2["current_page"] == $p3["current_page"] &&
                    $p3["current_page"] == $p4["current_page"]
                )
                    $database->resetGame();

                return new Response($kernel->getTemplates()->results(
                    $p1,
                    $p2,
                    $p3,
                    $p4,
                    $game["page_start"],
                    $game["page_end"],
                    $game["server_count_transitions"]
                ), 200);
            }

            // Wait for another player move
            if ($game["current_player_id"] != $currentPlayer["id"]) {
                return new Response($kernel->getTemplates()->anotherPlayerIsChoosing(
                    $game["page_start"],
                    $game["page_end"],
                    $currentPlayer["current_page"],
                    $currentPlayer["score"]
                ), 200);
            }

            // Current player move
            return new Response($kernel->getTemplates()->quiz(
                $game["page_start"],
                $game["page_end"],
                $currentPlayer["current_page"],
                $currentPlayer["score"],
                $wiki->getReferences($currentPlayer["current_page"]),
                $sessionKey
            ), 200);
        }

        /* User registration */

        if (!$request->isPOST())
            return new BadRequestResponse();

        $post = $request->getPostParams();

        if (!array_key_exists("username", $post) || count($players) >= 4)
            return new BadRequestResponse();

        $session_key = $database->registerUser($post["username"]);
        return new RedirectResponse("/?session_key=" . $session_key);
    }


    private function generateGame(Kernel $kernel)
    {
        $database = $kernel->getDatabaseManager();
        $wiki = $kernel->getWikipediaParser();

        $startPageName = $wiki->getRandomPage();
        $page = $wiki->getReferences($startPageName);
        $times = rand(1,10);

        for ($i = 0; $i < $times - 1; $i++) {
            if (count($page) <= 0) break;
            $page = $wiki->getReferences($page[array_rand($page)]);
        }

        $lastPageName = (count($page) <= 0) ? "" : $page[array_rand($page)];

        $database->createGame($startPageName, $lastPageName, $times);
    }
}