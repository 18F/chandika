<?
require "autoload.php";
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

if (key_exists("action", $_REQUEST)) {
    switch ($_REQUEST["action"]) {
        case "CREATE":
            ApiKeyAdministrator::create($_REQUEST["label"]);
            break;
        case "DELETE":
            ApiKeyAdministrator::delete($_REQUEST["id"]);
            break;
    }
}

include "header.php";
?>
<div class="container-fluid">
    <h1>API keys</h1>
    <table class="table-striped">
        <tr>
            <th>API key</th>
            <th>Label</th>
            <th>Last used</th>
            <th>Actions</th>
        </tr>
        <?
        $api_keys = ApiKeyAdministrator::apiKeys();
        foreach ($api_keys as $row) {
            $last_used = $row->last_used == null ? "Never" : $row->last_used;
            print "<tr><td>{$row->uuid}</td><td>{$row->label}</td>
                   <td>$last_used</td>
                   <td><a href='show_api_keys.php?action=DELETE&id={$row->id}'>Delete</a></td></tr>";
        }
        ?>
    </table>
    <hr />
    <h2>Add API key</h2>
    <form action="show_api_keys.php" method="POST">
        <input type="hidden" name="action" value="CREATE"/>
        <label for="label">Label</label> <input type="text" name="label" id="label"/>
        <button type="Submit" value="Add">Create API key</button>
    </form>
</div>
</body>
</html>