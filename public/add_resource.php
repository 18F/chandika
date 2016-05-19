<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

$service_id = $_REQUEST["service_id"];

if ($_REQUEST["action"] == "CREATE") {
  $resources = new ResourceAdministrator($service_id);
  $uri = $_REQUEST["uri"];
  $resource_type = $_REQUEST["resource_type"];
  $resources->create($resource_type, $_SESSION["user_email"], $uri, time());
}
if ($_REQUEST["action"] == "DELETE") {
  ResourceAdministrator::delete($_REQUEST["id"]);
}

header("Location: /show_resources.php?service_id=$service_id");
?>
