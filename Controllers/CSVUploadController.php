<?php

class CSVUploadController extends AppController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->render("csv_uploader");
    }
}
