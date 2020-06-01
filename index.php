<?php

define('SECURE_ROUTE', true);

//Routing done here

$request = $_SERVER['REQUEST_URI'];
$router_path = $_SERVER["PHP_SELF"];
$query_str = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "";

$actual_router = str_replace("/index.php", "", $router_path);
$route = str_replace($actual_router, "", $request);
if (!empty($query_str)) {
    $route = str_replace("?".$query_str, "", $route);
}


switch ($route) {
    case '/parser':
        require_once("Controllers/csv_parser.php");
        break;
    case '/items':
        require_once("Controllers/csv_items.php");
        break;
    case '/export':
        require_once("Controllers/export_content.php");
        break;
    default:
        require_once("Controllers/csv_uploader.php");
        break;
}
