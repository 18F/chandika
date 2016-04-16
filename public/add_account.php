<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});

session_start();
$accounts = new AccountAdministrator();
$nickname = $_REQUEST["nickname"];
$provider = $_REQUEST["provider"];
$identifier = $_REQUEST["identifier"];
$accounts->create($nickname, $provider, $identifier);
header("Location: /show_accounts.php");
?>
