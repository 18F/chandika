<?
require "autoload.php";
$auth = new Authenticator();
$sa = new ServiceAdministrator($auth);
if (key_exists("action", $_REQUEST) && $_REQUEST["action"] == "FILTER") {
    $_SESSION["account_id_filter"] = substr($_REQUEST["account_id"], 1);
    $_SESSION["archived_service_filter"] = key_exists("show_archived", $_REQUEST);
}
$accounts = [];
@array_walk(AccountAdministrator::accounts(), function ($value, $key) use (&$accounts, &$auth) {
    if ($value->is_archived == 0 && ($auth->belongsTo(Authenticator::administrator) || $value->is_prod == 0)) {
        $accounts["a{$value->id}"] = $value->label;
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
        Filter by account: <?= Filter::dropdown("account_id", array_merge(["a0" => "--All"], $accounts), "a".$account_selected) ?>
        | <input type="checkbox" name="show_archived"<?= $checked ?>/> Show archived systems
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
    <form action="edit_service.php" method="POST">
        <?= ServiceAdministrator::form(["account_id" => $accounts], []) ?>
        <button type="submit" name="action" value="CREATE">Add</button>
    </form>
</div>
</body>
</html>