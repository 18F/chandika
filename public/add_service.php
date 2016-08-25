<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

$services = new ServiceAdministrator();
$properties = $_REQUEST;
$properties["is_archived"] = isset($_REQUEST["archived"]) ? 1 : 0;
$services->create($properties);
header("Location: /show_services.php");
?>
