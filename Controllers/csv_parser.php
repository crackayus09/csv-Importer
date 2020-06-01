<?php
if (!defined('SECURE_ROUTE')) {
    die('<h1  style="text-align: center;">Direct File Access Prohibited</h1>');
}

$action = (isset($_POST["action"]) && $_POST["action"]) ? $_POST["action"] : "";

require_once("includes/config.php");

if (!empty($action) && $action === "upload" && !empty($_FILES) && $_FILES["file"]["error"] == 0) {
    $mimes = ['application/vnd.ms-excel','text/plain','text/csv','text/tsv'];
    if (!in_array($_FILES['file']['type'], $mimes)) {
        $response = [
            "success" => false,
            "message" => "Invalid File Type."
        ];
    } else {
        require_once("Classes/CSVParser.php");
        $ob_csv_parser = new CSVParser();
    
        $var_uniq_id = uniqid();
        $file_name = "uploaded_file_" . $var_uniq_id;
        $file_folder = UPLOAD_PATH . $file_name . ".csv";
        move_uploaded_file($_FILES['file']['tmp_name'], $file_folder);
            
        $ob_csv_parser->file_path = $file_folder;
        $csv_array = $ob_csv_parser->csv_to_array();
    
        $fp = fopen(JSON_PATH . $file_name . ".json", 'w');
        fwrite($fp, json_encode($csv_array));
        fclose($fp);
    
        session_start();
        $_SESSION["file_name"] = $file_name;
    
        $response = [
            "success" => true,
            "message" => "Data fetched successfully.",
            "headers" => array_keys($csv_array[0]),
            "body" => $csv_array
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
