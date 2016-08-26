<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$sa = new ServiceAdministrator($auth);
if (key_exists("action", $_REQUEST) && $_REQUEST["action"] == "FILTER") {
    $_SESSION["account_id_filter"] = $_REQUEST["account_id"];
    $_SESSION["archived_service_filter"] = key_exists("show_archived", $_REQUEST);
}
$accounts = [];
$accounts[0] = "--All";
@array_walk(AccountAdministrator::accounts(), function ($value, $key) use (&$accounts, &$auth) {
    if ($auth->belongsTo(Authenticator::administrator) || $value->is_prod == 0) {
        $accounts[$value->id] = $value->label;
    }
});

$account_selected = key_exists("account_id_filter", $_SESSION) ? $_SESSION["account_id_filter"] : 0;
$show_archived = key_exists("archived_service_filter", $_SESSION) ? $_SESSION["archived_service_filter"] : false;
$checked = $show_archived ? " checked" : "";

include "header.php";
?>
<div class="container-fluid">
    <h1>Systems</h1>
    <form action="show_services.php" method="get">
        Filter by account: <?= Filter::dropdown("account_id", $accounts, $account_selected) ?>
        | <input type="checkbox" name="show_archived"<?=$checked?>/> Show archived services
        <button type="submit" name="action" value="FILTER">Filter</button>
    </form>
    <hr/>
    <table class="table-striped">
        <tr>
            <th>Name</th>
            <th>Account</th>
            <th>GitHub repo</th>
            <th>System URL</th>
            <th>Owner</th>
            <th>Billing code</th>
            <th>Infrastructure Tag</th>
            <th>Archived?</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?
        foreach ($sa->services_filtered($account_selected, $show_archived) as $row) {
            $is_archived = $row->is_archived == 1 ? "Yes" : "No";
            print "<tr><td>{$row->name}</td><td>{$row->label}</td><td>{$row->repository}</td><td>{$row->url}</td><td>{$row->owner}</td>
                   <td>{$row->billing_code}</td><td>{$row->tag}</td><td>$is_archived</td><td>$row->description</td>
                   <td><a href='edit_service.php?service_id={$row->id}'>Edit</a> |
                   <a href='show_resources.php?service_id={$row->id}'>Resources</a> |
                   <a href='show_billing_service.php?service_id={$row->id}'>Billing</a></td></tr>";
        }
        ?>
    </table>
    <hr/>
    <h2>Add system</h2>
    <form action="add_service.php" method="POST">
        <input type="hidden" name="action" value="CREATE"/>
        <label for="name">System name</label> <input type="text" name="name" id="name"/><br/>
        <label for="owner">Owner's email id</label> <input type="text" name="owner" id="owner"/><br/>
        <label for="account">Account</label> <?= Filter::dropdown("account_id", $accounts, 0) ?><br/>
        <label for="repository">GitHub repo</label> <input type="text" name="repository" id="repository"/><br/>
        <label for="url">Service URL</label> <input type="text" name="url" id="url"/><br>
        <label for="tag">Infrastructure Tag</label> <input type="text" name="tag" id="tag"/><br/>
        <label for="billing_code">Billing code (TOCK)</label> <input type="text" name="billing_code" id="billing_code"/><br/>
        <label for="description">Description</label> <input type="text" name="description" id="description"/><br>
        <input type="checkbox" name="is_archived"/> Archived<br/>
        <input type="submit" name="action" value="Add"/>
    </form>
</div>
</body>
</html>