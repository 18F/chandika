<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

$users = new UserAdministrator();
switch ($_REQUEST["action"]) {
    case "CREATE":
        $email = $_REQUEST["email"];
        $users->create($email);
        break;
    case "DELETE":
        $id = $_REQUEST["id"];
        $users->delete($id);
}
header("Location: /show_administrators.php");
?>
