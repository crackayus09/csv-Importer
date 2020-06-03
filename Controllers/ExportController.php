<?php

class ExportController extends AppController
{
    public function __construct()
    {
        parent::__construct();

        $post_data = file_get_contents('php://input');
        $post_data = json_decode($post_data, true);

        $this->exp_option = $this->arr_extract($post_data, "exp_option");
        $this->filters = $this->arr_extract($post_data, "filters", []);

        $this->file_name = $this->arr_extract($_SESSION, "file_name");
    }
    public function index()
    {
        if (!empty($this->file_name) && !empty($this->exp_option)) {
            require_once("Models/ExportModel.php");
            $ob_exporter = new ExportModel($this->file_name, $this->filters);

            if ($ob_exporter->filtered_count > 0) {
                switch ($this->exp_option) {
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
    }
}
