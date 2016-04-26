<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

$accounts = new AccountAdministrator();
$nickname = $_REQUEST["nickname"];
$provider = $_REQUEST["provider"];
$identifier = $_REQUEST["identifier"];
$accounts->create($nickname, $provider, $identifier);
header("Location: /show_accounts.php");
?>
