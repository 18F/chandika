<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

$service_id = $_REQUEST["service_id"];
$resources = new ResourceAdministrator($service_id);
$uri = $_REQUEST["uri"];
$resource_type = $_REQUEST["resource_type"];
$resources->create($resource_type, "Jez", $uri, time());
header("Location: /show_resources.php?service_id=$service_id");
?>
