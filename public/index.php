<?php
/**
 * Created by PhpStorm.
 * User: Shameel
 * Date: 06/10/2018
 * Time: 22:42
 */

main::start("example.csv");
class main  {
    static public function start($filename) {
        $records = csv::getRecords($filename);
        $table = html::generateTable($records);
        system::printPage($table);
    }
}
class html {
    public static function generateTable($records) {
        $html = '<table class="table table-striped">';
        $html .= '<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">';
        $count = 0;
        foreach ($records as $record) {
            if($count == 0) {
                $array = $record->returnArray();
                $fields = array_keys($array);
                $values = array_values($array);
                $row = html::tableHeader($fields);
                $html .= html::tableRow($row);
                $row = html::tableColumn($values);
                $html .= html::tableRow($row);
                //print_r($fields);
                //print_r($values);
            } else {
                $array = $record->returnArray();
                $values = array_values($array);
                $row = html::tableColumn($values);
                $html .= html::tableRow($row);
                //print_r($values);
            }
            $count++;
        }
        $html .= '</table>';
        return $html;
    }
    public static function tableRow($row) {
        $html = '<tr>';
        $html .= $row;
        $html .= '</tr>';
        return $html;
    }
    public static function tableColumn($values) {
        $html = '';
        foreach ($values as $value) {
            $html .= '<td>';
            $html .= $value;
            $html .= '</td>';
        }
        return $html;
    }
    public static function tableHeader($fields) {
        $html = '';
        foreach ($fields as $field) {
            $html .= '<th>';
            $html .= $field;
            $html .= '</th>';
        }
        return $html;
    }

}
class csv {
    static public function getRecords($filename) {
        $file = fopen($filename,"r");
        $fieldNames = array();
        $count = 0;
        while(! feof($file))
        {
            $record = fgetcsv($file);
            if($count == 0) {
                $fieldNames = $record;
            } else {
                $records[] = recordFactory::create($fieldNames, $record);
            }
            $count++;
        }
        fclose($file);
        return $records;
    }
}
class system {
    public static function printPage($page) {
        echo $page;
    }
}
class record {
    public function __construct(Array $fieldNames = null, $values = null )
    {
        $record = array_combine($fieldNames, $values);
        foreach ($record as $property => $value) {
            $this->createProperty($property, $value);
        }
    }
    public function returnArray() {
        $array = (array) $this;
        return $array;
    }
    public function createProperty($name = 'first', $value = 'keith') {
        $this->{$name} = $value;
    }
}
class recordFactory {
    public static function create(Array $fieldNames = null, Array $values = null) {
        $record = new record($fieldNames, $values);
        return $record;
    }
}