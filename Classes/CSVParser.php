<?php
class CSVParser
{
    //Properties
    public $csv_content;
    // Methods
    public function csv_to_array()
    {
        $csv_lines = explode("\n", file_get_contents($this->file_path));
        $headers = str_getcsv(array_shift($csv_lines));
        $csv_data = array();
        foreach ($csv_lines as $csv_line) {
            $row = array();
        
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
}
