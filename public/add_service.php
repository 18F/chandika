<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

$services = new ServiceAdministrator();
$services->create($_REQUEST);
header("Location: /show_services.php");
?>
