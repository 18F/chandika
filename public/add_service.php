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
$is_billable = $_REQUEST["is_billable"];
$services->create($name, $account_id, $repository, $url, $owner, $is_billable, $tag);
header("Location: /show_services.php");
?>
