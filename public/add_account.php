<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

AccountAdministrator::create($_REQUEST["label"], $_REQUEST["provider"], $_REQUEST["identifier"], $_REQUEST["email"],$_REQUEST["description"]);
header("Location: /show_accounts.php");
?>
