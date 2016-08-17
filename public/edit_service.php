<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

$accounts = [];
@array_walk(AccountAdministrator::accounts(), function($value, $key) use (&$accounts) { $accounts[$value->id] = $value->nickname; });

$service_id = $_REQUEST["service_id"];
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "Update") {
    ServiceAdministrator::update($service_id, $_REQUEST["name"], $_REQUEST["owner"], $_REQUEST["account_id"], $_REQUEST["repository"], $_REQUEST["url"], $_REQUEST["billing_code"], $_REQUEST["tag"]);
}
$service = ServiceAdministrator::service($service_id);

include "header.php";
?>
<section id="main">
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
        <input type="submit" name="action" value="Update"/>
    </form>
</section>
</body>
</html>
