<?php
class Request implements ArrayAccess {
    public $array = [];
    private $whiteKey = [
        'watch',
        'channel',
        ''
    ];
    function __construct($GetArray) {
        foreach ($GetArray as $key => $value) {
            $key = htmlentities($key);

            if(empty($value)) continue;
            //if(in_array($key, $whiteKey)) continue;
            //if(strlen($key) > 255) unset()

            //preg_match()

            if(!is_array($value)) $this->array[$key] = htmlentities($value);
            if(is_array($value)) $this->array[$key] = $value;
        }
    }
    public function offsetSet($offset, $value): void {
        if (is_null($offset))
            $this->array[] = $value;
        else
            $this->array[$offset] = $value;
    }
    public function offsetExists($offset): bool {
        return isset($this->array[$offset]);
    }
    public function offsetUnset($offset): void {
       unset($this->array[$offset]);
    }
    public function offsetGet($offset): mixed {
        return (isset($this->array[$offset]) || !empty($this->array[$offset])) ? $this->array[$offset] : false;
    }
}