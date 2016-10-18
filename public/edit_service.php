<?
require "autoload.php";
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);
$sa = new ServiceAdministrator($auth);

if (isset($_REQUEST["action"])) {
    switch ($_REQUEST["action"]) {
        case "UPDATE":
            $sa->update($_REQUEST["service_id"], $_REQUEST);
            break;
        case "CREATE":
            $sa->create($_REQUEST);
            break;
    }
    header("Location: /show_services.php");
    die();
}

$service_id = $_REQUEST["service_id"];
$accounts = [];
@array_walk(AccountAdministrator::accounts(), function ($value, $key) use (&$accounts) {
    $accounts[$value->id] = $value->label;
});

$service = $sa->service($service_id);

include "header.php";
?>
<div class="container-fluid">
    <h1>Edit system</h1>
    <form action="edit_service.php" method="POST">
        <input type="hidden" name="service_id" value="<?= $service_id ?>"/>
        <?= $sa::form(["account_id" => $accounts], $service); ?>
        <button type="submit" name="action" value="UPDATE">Update</button>
    </form>
</div>
</body>
</html>
