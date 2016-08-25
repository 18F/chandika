<?php
class CrudHelper
{
    private $fields;

    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    public function bind(&$insert, $properties)
    {
        foreach ($this->fields as $field) {
            $name = $field["name"];
            $insert->bindParam(":$name", $properties[$name]);
        }
    }
}