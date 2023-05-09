<?php

use Kernel\Kernel;

define('FRAMEWORK_START', microtime(true));

/** @var Kernel $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handle();