<?
class Filter
{
    public function dropdown($name, $values, $selected) {
        $dropdown = "<select name='$name'>";
        foreach ($values as $key => $label) {
            $selected_text = $key == $selected ? " selected" : "";
            $dropdown .= "<option value='$key'{$selected_text}>$label</option>";
        }
        return $dropdown."</select>";
    }
}
?>