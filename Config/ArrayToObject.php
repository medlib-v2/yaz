<?php

namespace Yaz\Config;

use stdClass;

class ArrayToObject
{
    public function __construct(array $array)
    {
        $this->ArrayObject($array);
    }



    private function ArrayObject($array) {

        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name=>$value) {
                if(!is_numeric($name))
                {
                    $name = strtolower(trim($name));
                    if (!empty($name)) {
                        $object->$name = $this->ArrayObject($value);
                    }
                }
                else {
                    $object->$name = $value;
                }
            }
            return $object;
        }
        else {
            return FALSE;
        }
    }
}