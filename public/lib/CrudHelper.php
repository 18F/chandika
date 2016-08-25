<?php
class CrudHelper
{
    const checkbox = "checkbox";
    private $fields;

    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    public function bind(&$statement, &$properties)
    {
        foreach ($this->fields as $field) {
            $name = $field["name"];
            if (key_exists("type", $field)) {
                switch ($field["type"]) {
                    case self::checkbox:
                        $value = isset($properties[$name]) ? 1 : 0;
                        break;
                }
            } else {
                $value = $properties[$name];
            }
            $statement->bindValue(":$name", $value);
        }
    }
}