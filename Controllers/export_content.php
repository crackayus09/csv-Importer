<?php
if (!defined('SECURE_ROUTE')) {
    die('<h1  style="text-align: center;">Direct File Access Prohibited</h1>');
}

session_start();

require_once("includes/config.php");

$post_data = file_get_contents('php://input');
$post_data = json_decode($post_data, true);

$exp_option = (isset($post_data["exp_option"]) && $post_data["exp_option"]) ? $post_data["exp_option"] : "";
$filters = (isset($post_data["filters"]) && $post_data["filters"]) ? $post_data["filters"] : [];

$file_name = (isset($_SESSION["file_name"]) && $_SESSION["file_name"]) ? $_SESSION["file_name"] : "";

if (!empty($file_name) && !empty($exp_option)) {
    $json_data = file_get_contents(JSON_PATH . $file_name . ".json");
    $data_arr = json_decode($json_data, true);

    require_once("Classes/CSVParser.php");
    $ob_csv_parser = new CSVParser();

    $ob_csv_parser->csv_content = $data_arr;
    $ob_csv_parser->params = $filters;

    $filtered_content = $ob_csv_parser->csv_filter();

    $data_filtered = $filtered_content["data"];
    $filtered_count = $filtered_content["itemsCount"];
    if ($filtered_count > 0) {
        require_once("Classes/Exporter.php");
        $ob_exporter = new Exporter();

        $ob_exporter->data = $data_filtered;

        switch ($exp_option) {
            case 'pdf':
                $export_file = $ob_exporter->exportPdf();
                break;
            case 'excel':
                $export_file = $ob_exporter->exportExcel();
                break;
            default:
                $export_file = $ob_exporter->exportCsv();
                break;
        }
        $response = [
            "success" => true,
            "message" => "File successfully exported.",
            "data" => $export_file
        ];
    } else {
        $response = [
            "success" => false,
            "message" => "No Data to export."
        ];
    }
} else {
    $response = [
        "success" => false,
        "message" => "Invalid Access."
    ];
}

echo json_encode($response);
exit;
