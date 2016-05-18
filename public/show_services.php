<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

$accounts = [];
@array_walk(AccountAdministrator::accounts(), function($value, $key) use (&$accounts) { $accounts[$value->id] = $value->nickname; });

include "header.php";
?>
<section id="main">
    <h1>Systems</h1>
    <table class="table-striped">
        <tr>
            <th>Name</th>
            <th>Account</th>
            <th>GitHub repo</th>
            <th>System URL</th>
            <th>Owner</th>
            <th>Billable?</th>
            <th>Actions</th>
        </tr>
        <?
        foreach (ServiceAdministrator::services() as $row) {
            $billable = $row->is_billable == 1 ? "Yes" : "No";
            print "<tr><td>{$row->name}</td><td>{$row->nickname}</td><td>{$row->repository}</td><td>{$row->url}</td><td>{$row->owner}</td>
                   <td>$billable</td><td><a href='edit_service.php?service_id={$row->id}'>Edit</a> |
                   <a href='show_resources.php?service_id={$row->id}'>Resources</a></td></tr>";
        }
        ?>
    </table>
    <hr/>
    <h2>Add system</h2>
    <form action="add_service.php" method="POST">
        <input type="hidden" name="action" value="CREATE"/>
        <label for="name">System name</label> <input type="text" name="name" id="name"/><br/>
        <label for="owner">Owner's email id</label> <input type="text" name="owner" id="owner"/><br/>
        <label for="account">Account</label> <?= Filter::dropdown("account_id", $accounts, 0)?><br/>
        <label for="repository">GitHub repo</label> <input type="text" name="repository" id="repository"/><br/>
        <label for="url">Service URL</label> <input type="text" name="url" id="url"/><br>
        <label for="billable">Who will pay the costs associated with operating this system?</label> <?= Filter::dropdown("is_billable", [ "0" => "TTS (operating expense)", "1" => "Another agency (cost of goods sold)"], 1)?><br/>
        <input type="submit" name="action" value="Add"/>
    </form>
</section>
</body>
</html>