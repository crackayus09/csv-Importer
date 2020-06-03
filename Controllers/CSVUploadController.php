<?php

class CSVUploadController extends AppController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $previous_files = $this->arr_extract($_SESSION, "previous_files", []);
        $this->render("csv_uploader", ["previous_files" => $previous_files]);
    }
}
