<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

AccountAdministrator::create($_REQUEST["nickname"], $_REQUEST["provider"], $_REQUEST["identifier"], $_REQUEST["email"]);
header("Location: /show_accounts.php");
?>
