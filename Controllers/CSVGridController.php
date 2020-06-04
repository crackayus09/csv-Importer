<?php

/**
 * Class CSVGridController
 */
class CSVGridController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->fileName = $this->arrExtract($_SESSION, "file_name");

        $get_params = $_GET;

        $page = $this->arrExtract($get_params, "pageIndex", 1);
        $this->pageSize = $this->arrExtract($get_params, "pageSize", 10);

        unset($get_params["pageIndex"]);
        unset($get_params["pageSize"]);

        $this->params = $get_params;

        $this->start = ($page - 1) * $this->pageSize;
    }

    /**
     * Used to render the CSV grid
     */
    public function index()
    {
        if (!empty($this->fileName)) {
            $json_data = file_get_contents(JSON_PATH . $this->fileName . ".json");
            $data_arr = json_decode($json_data, true);

            require_once("Models/CSVParserModel.php");
            $ob_csv_parser = new CSVParserModel();

            $ob_csv_parser->csvContent = $data_arr;
            $ob_csv_parser->params = $this->params;

            $ob_csv_parser->start = $this->start;
            $ob_csv_parser->pageSize = $this->pageSize;

            $response = $ob_csv_parser->csvFilter();
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
