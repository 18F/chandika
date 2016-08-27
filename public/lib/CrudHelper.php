<?php
class CrudHelper
{
    const checkbox = "checkbox";
    const name = "name";
    const desc = "description";
    const type = "type";
    const dropdown = "dropdown";
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
                    default:
                        $value = $properties[$name];
                        break;
                }
            } else {
                $value = $properties[$name];
            }
            $statement->bindValue(":$name", $value);
        }
    }

    public function form($options, $selected)
    {
        $output = "";
        $values = (array) $selected;
        foreach ($this->fields as $field) {
            $name = $field["name"];
            $value = key_exists($name, $values) ? $values[$name] : "";
            if (key_exists("type", $field)) {
                switch ($field["type"]) {
                    case self::checkbox:
                        $checked = (key_exists($name, $values) && $values[$name] == 1) ? " checked" : "";
                        $output .= "<input type='checkbox' name='$name'$checked/> {$field["description"]}<br/>";
                        break;
                    case self::dropdown:
                        $option = key_exists($name, $values) ? $values[$name] : "";
                        $output .= "<label for='name'>{$field["description"]}</label> ".Filter::dropdown($name, $options[$name], $option)."<br/>";
                        break;
                }
            } else {
                $output .= "<label for='$name'>{$field["description"]}</label> <input type='text' name='$name' id='$name' value='$value'/><br/>";
            }
        }
        return $output;
    }
}