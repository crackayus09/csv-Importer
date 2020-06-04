<?php

/**
 * Class CSVParseController
 */
class CSVParseController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->postDataArr = $this->postData();
        $this->action = $this->arrExtract($this->postDataArr, "action");
    }

    /**
     * Used to upload a CSV file and prepare it
     */
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

                $ob_csv_parser->filePath = $file_folder;
                $csv_array = $ob_csv_parser->csvToArray();

                $fp = fopen(JSON_PATH . $file_name . ".json", 'w');
                fwrite($fp, json_encode($csv_array));
                fclose($fp);

                $_SESSION["file_name"] = $file_name;
                $_SESSION["previous_files"][] = [
                    "user_file_name" => $_FILES["file"]["name"],
                    "actual_name" => $file_name
                ];

                $response = [
                    "success" => true,
                    "message" => "Data fetched successfully.",
                    "headers" => array_keys($csv_array[0]),
                    "previous_files" => $_SESSION["previous_files"]
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

    /**
     * Used to show contents of previous files
     */
    public function previous()
    {
        $response = [
            "success" => false,
            "message" => "Invalid Access."
        ];
        if (!empty($this->action) && $this->action === "previous") {
            $file_name = $this->arrExtract($this->postDataArr, "file_name");
            if (!empty($file_name)) {
                $file_content = file_get_contents(JSON_PATH . $file_name . ".json");
                if (!empty($file_content)) {
                    $_SESSION["file_name"] = $file_name;
                    $file_arr = json_decode($file_content, true);
                    $response = [
                        "success" => true,
                        "message" => "Data fetched successfully.",
                        "headers" => array_keys($file_arr[0])
                    ];
                }
            }
        }

        echo json_encode($response);
        exit;
    }
}
