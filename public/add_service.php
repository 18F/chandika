<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

$services = new ServiceAdministrator();
$name = $_REQUEST["name"];
$account_id = $_REQUEST["account_id"];
$repository = $_REQUEST["repository"];
$owner = $_REQUEST["owner"];
$url = $_REQUEST["url"];
$tag= $_REQUEST["tag"];
$billing_code = $_REQUEST["billing_code"];
$services->create($name, $account_id, $repository, $url, $owner, $billing_code, $tag);
header("Location: /show_services.php");
?>
