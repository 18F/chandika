<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);
$sa = new ServiceAdministrator($auth);

$service_id = $_REQUEST["service_id"];
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "Update") {
    $sa->update($service_id, $_REQUEST);
    header("Location: /show_services.php");
    die();
}

$accounts = [];
@array_walk(AccountAdministrator::accounts(), function($value, $key) use (&$accounts) { $accounts[$value->id] = $value->label; });

$service = $sa->service($service_id);
$checked = $service->is_archived == 1 ? " checked" : "";

include "header.php";
?>
<div class="container-fluid">
    <h1>Edit system</h1>
    <form action="edit_service.php" method="POST">
        <input type="hidden" name="service_id" value="<?= $service_id?>"/>
        <label for="name">System name</label> <input type="text" name="name" id="name" value="<?= $service->name?>"/><br/>
        <label for="owner">Owner's email id</label> <input type="text" name="owner" id="owner" value="<?= $service->owner?>"/><br/>
        <label for="account">Account</label> <?= Filter::dropdown("account_id", $accounts, $service->account_id)?><br/>
        <label for="repository">GitHub repo</label> <input type="text" name="repository" id="repository" value="<?= $service->repository?>"/><br/>
        <label for="url">Service URL</label> <input type="text" name="url" id="url" value="<?= $service->url?>"/><br>
        <label for="tag">Infrastructure tag</label> <input type="text" name="tag" id="tag" value="<?= $service->tag?>"/><br>
        <label for="billing_code">Billing code (TOCK)</label> <input type="text" name="billing_code" id="billing_code" value="<?= $service->billing_code?>"/><br>
        <label for="description">Description</label> <input type="text" name="description" id="description" value="<?= $service->description?>"/><br>
        <input type="checkbox" name="is_archived" <?=$checked?>/> Archived<br/>
        <input type="submit" name="action" value="Update"/>
    </form>
</div>
</body>
</html>
