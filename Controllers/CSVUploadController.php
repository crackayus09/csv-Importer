<?php

/**
 * Class CSVUploadController
 */
class CSVUploadController extends AppController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Used to render csv_uploader view
     */
    public function index()
    {
        $previous_files = $this->arrExtract($_SESSION, "previous_files", []);
        $this->render("csv_uploader", ["previous_files" => $previous_files]);
    }
}
