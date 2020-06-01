<?php
if (!defined('SECURE_ROUTE')) {
    die('<h1  style="text-align: center;">Direct File Access Prohibited</h1>');
}

session_start();

require_once("includes/config.php");

$file_name = (isset($_SESSION["file_name"]) && $_SESSION["file_name"]) ? $_SESSION["file_name"] : "";

if (!empty($file_name)) {
    $json_data = file_get_contents(JSON_PATH . $file_name . ".json");
    $data_arr = json_decode($json_data, true);
    
    $page = (isset($_GET["pageIndex"]) && $_GET["pageIndex"]) ? $_GET["pageIndex"] : 1;
    $page_size = (isset($_GET["pageSize"]) && $_GET["pageSize"]) ? $_GET["pageSize"] : 10;
    $start = ($page - 1) * $page_size;

    $get_params = $_GET;

    unset($get_params["pageIndex"]);
    unset($get_params["pageSize"]);

    require_once("Classes/CSVParser.php");
    $ob_csv_parser = new CSVParser();

    $ob_csv_parser->csv_content = $data_arr;
    $ob_csv_parser->params = $get_params;

    $ob_csv_parser->start = $start;
    $ob_csv_parser->page_size = $page_size;

    $response = $ob_csv_parser->csv_filter();
} else {
    $response = [
        "success" => false,
        "message" => "Invalid Access."
    ];
}

echo json_encode($response);
exit;
