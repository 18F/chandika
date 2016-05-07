<?
class Select
{
    public static function render($name, $options, $selected) {
        $output = "<select name='{$name}'>";
        foreach ($options as $key => $value) {
            $selected = $key == $selected ? " selected" : "";
            $output .= "<option value='$key'$selected>$value</option>";
        }
        $output .= "</select>\n";
        return $output;
    }
}
?>