<?php

class CSVParserModel extends AppModel
{
    //Properties
    public $csv_content;
    // Methods

    public function __construct()
    {
        parent::__construct();
    }
    public function csv_to_array()
    {
        $file_content = file_get_contents($this->file_path);
        $file_content = $this->normalize($file_content);
        $csv_lines = explode("\n", $file_content);
        $headers = str_getcsv(array_shift($csv_lines));
        $csv_data = [];
        foreach ($csv_lines as $csv_line) {
            if (empty($csv_line)) {
                continue;
            }
            $row = [];
            foreach (str_getcsv($csv_line) as $key => $field) {
                $row[ $headers[ $key ] ] = $field;
            }
            $csv_data[] = $row;
        }
        $this->csv_content = $csv_data;
        return $csv_data;
    }
    public function csv_filter()
    {
        $data_filtered = $this->csv_content;
        $params = $this->params;

        foreach ($params as $key => $param) {
            if (!empty($param)) {
                $data_filtered = array_filter($data_filtered, function ($v) use ($key, $param) {
                    return (strpos(strtolower($v[$key]), strtolower($param)) !== false) ? true : false;
                });
            }
        }

        $t_count = count($data_filtered);

        if (isset($this->start) && isset($this->page_size)) {
            $data_filtered = array_slice($data_filtered, $this->start, $this->page_size);
        }
        $data_filtered = array_values($data_filtered);
        $data_filtered = array_map(
            function ($ar) use ($params) {
                $param_keys = array_keys($params);
                $new_arr = [];
                foreach ($param_keys as $key) {
                    $new_arr[$key] = $ar[$key];
                }
                return $new_arr;
            },
            $data_filtered
        );
        return ["data" => $data_filtered, "itemsCount" => $t_count];
    }

    private function normalize($s)
    {
        $s = str_replace("\r\n", "\n", $s);
        $s = str_replace("\r", "\n", $s);
        $s = preg_replace("/\n{2,}/", "\n\n", $s);
        return $s;
    }
}
