<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;

class Exporter
{
    // Methods
    public function exportCsv()
    {
        $data_arr = $this->data;
        $headers = array_keys($data_arr[0]);

        $file_path = $this->fileName("csv");

        $csv_file = fopen($file_path, 'w');
        fputcsv($csv_file, $headers);
        foreach ($data_arr as $row) {
            fputcsv($csv_file, $row);
        }
        fclose($csv_file);

        return $file_path;
    }
    public function exportPdf()
    {
        $s_sheet = $this->createSpreadSheet();
        $writer = new Tcpdf($s_sheet);
        $file_path = $this->fileName("pdf");
        $writer->save($file_path);
        return $file_path;
    }
    public function exportExcel()
    {
        $s_sheet = $this->createSpreadSheet();
        $writer = new Xlsx($s_sheet);

        $file_path = $this->fileName("xlsx");

        $writer->save($file_path);
        return $file_path;
    }
    private function createSpreadSheet()
    {
        $data_arr = $this->data;
        $headers = array_keys($data_arr[0]);

        $row_prefixs = $this->excelRows();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        for ($index = 0; $index < count($headers); $index++) {
            $sheet->setCellValue($row_prefixs[$index] . '1', $headers[$index]);
        }
        foreach ($data_arr as $key => $row) {
            for ($index = 0; $index < count($headers); $index++) {
                $sheet->setCellValue($row_prefixs[$index] . ($key + 2), $row[$headers[$index]]);
            }
        }
        return $spreadsheet;
    }
    private function excelRows()
    {
        $row_format = [];
        for ($x = 'A'; $x < 'ZZZ'; $x++) {
            $row_format[] = $x;
        }
        return $row_format;
    }
    private function fileName($format)
    {
        $var_uniq_id = uniqid();
        $file_name = "exported_file_" . $var_uniq_id;
        $file_folder = EXPORT_PATH . $file_name . "." . $format;
        return $file_folder;
    }
}
