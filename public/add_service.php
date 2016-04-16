<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});

session_start();
$services = new ServiceAdministrator();
$name = $_REQUEST["name"];
$account_id = $_REQUEST["account_id"];
$repository = $_REQUEST["repository"];
$url = $_REQUEST["url"];
$services->create($name, $account_id, $repository, $url);
header("Location: /show_services.php");
?>
