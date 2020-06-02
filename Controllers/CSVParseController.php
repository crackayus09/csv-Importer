<?php

class CSVParseController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->action = (isset($_POST["action"]) && $_POST["action"]) ? $_POST["action"] : "";
    }
    public function index()
    {
        if (!empty($this->action) && $this->action === "upload" && !empty($_FILES) && $_FILES["file"]["error"] == 0) {
            $mimes = ['application/vnd.ms-excel','text/plain','text/csv','text/tsv'];
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $response = [
                    "success" => false,
                    "message" => "Invalid File Type."
                ];
            } else {
                require_once("Models/CSVParserModel.php");
                $ob_csv_parser = new CSVParserModel();
            
                $var_uniq_id = uniqid();
                $file_name = "uploaded_file_" . $var_uniq_id;
                $file_folder = UPLOAD_PATH . $file_name . ".csv";
                move_uploaded_file($_FILES['file']['tmp_name'], $file_folder);
                    
                $ob_csv_parser->file_path = $file_folder;
                $csv_array = $ob_csv_parser->csv_to_array();
            
                $fp = fopen(JSON_PATH . $file_name . ".json", 'w');
                fwrite($fp, json_encode($csv_array));
                fclose($fp);
                
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
    }
}
