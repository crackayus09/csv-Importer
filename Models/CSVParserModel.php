<?php

/**
 * class CSVParserModel
 */
class CSVParserModel extends AppModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Used to convert CSV file to PHP array
     *
     * @return array
     */
    public function csvToArray()
    {
        $file_content = file_get_contents($this->filePath);
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
        $this->csvContent = $csv_data;
        return $csv_data;
    }

    /**
     * Used to filter filter array and extract selected columns
     *
     * return array
     */
    public function csvFilter()
    {
        $data_filtered = $this->csvContent;
        $params = $this->params;

        foreach ($params as $key => $param) {
            if (!empty($param)) {
                $data_filtered = array_filter($data_filtered, function ($v) use ($key, $param) {
                    return (strpos(strtolower($v[$key]), strtolower($param)) !== false) ? true : false;
                });
            }
        }

        $t_count = count($data_filtered);

        if (isset($this->start) && isset($this->pageSize)) {
            $data_filtered = array_slice($data_filtered, $this->start, $this->pageSize);
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

    /**
     * Used to normalize CRLF/LF/CR files to LF
     *
     * @param string $s
     *
     * @return string
     */
    private function normalize($s)
    {// Methods
        $s = str_replace("\r\n", "\n", $s);
        $s = str_replace("\r", "\n", $s);
        $s = preg_replace("/\n{2,}/", "\n\n", $s);
        return $s;
    }
}
