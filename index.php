<?php

define('SECURE_ROUTE', true);

require_once("./includes/core.php");

require_once("./router.php");
require_once("./request.php");
require_once("./dispatcher.php");

$dispatch = new Dispatcher();
$dispatch->dispatch();
