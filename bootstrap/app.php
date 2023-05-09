<?php

use Kernel\Kernel;

require __DIR__ . "/autoload.php";

autoload("Controller/Controller");
autoload("Controller/ErrorNotFoundController");
autoload("Controller/HomeController");

autoload("Response/Response");
autoload("Response/ErrorResponse");
autoload("Response/BadRequestResponse");
autoload("Response/NotFoundResponse");

autoload("Database/DatabaseManager");

autoload("Config");
autoload("Kernel");
autoload("Request");
autoload("Templates");
autoload("Router");


return (new Kernel());