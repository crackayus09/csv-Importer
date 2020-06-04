<?php

/**
 * Class ExportController
 */
class ExportController extends AppController
{
    public function __construct()
    {
        parent::__construct();

        $post_data = $this->postData();

        $this->expOption = $this->arrExtract($post_data, "exp_option");
        $this->filters = $this->arrExtract($post_data, "filters", []);

        $this->fileName = $this->arrExtract($_SESSION, "file_name");
    }

    /**
     * Used to export content to specific format
     */
    public function index()
    {
        if (!empty($this->fileName) && !empty($this->expOption)) {
            require_once("Models/ExportModel.php");
            $ob_exporter = new ExportModel($this->fileName, $this->filters);

            if ($ob_exporter->filteredCount > 0) {
                switch ($this->expOption) {
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
