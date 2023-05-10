<?php

use Kernel\Kernel;

require __DIR__ . "/autoload.php";

autoload("Controller/Controller");
autoload("Controller/ErrorNotFoundController");
autoload("Controller/HomeController");

autoload("Response/Response");
autoload("Response/JsonResponse");
autoload("Response/ErrorResponse");
autoload("Response/BadRequestResponse");
autoload("Response/NotFoundResponse");
autoload("Response/RedirectResponse");

autoload("Database/DatabaseManager");

autoload("Parser/WikipediaParser");

autoload("Config");
autoload("Kernel");
autoload("Request");
autoload("Templates");
autoload("Router");


return (new Kernel());