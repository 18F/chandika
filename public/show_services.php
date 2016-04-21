<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

include "header.php";
?>
<section id="main">
    <h1>Services</h1>
    <table class="table-striped">
        <tr>
            <th>Name</th>
            <th>Account</th>
            <th>GitHub repo</th>
            <th>Service URL</th>
            <th>Owner</th>
            <th>Actions</th>
        </tr>
        <?
        $services = new ServiceAdministrator();
        $accounts = new AccountAdministrator();
        foreach ($services->services() as $row) {
            print "<tr><td>{$row->name}</td><td>{$row->nickname}</td><td>{$row->repository}</td><td>{$row->url}</td><td>{$row->owner}</td>
                   <td><a href='show_resources.php?service_id={$row->id}'>Resources</a></td></tr>";
        }
        ?>
    </table>
    <hr/>
    <h2>Add service</h2>
    <form action="add_service.php" method="POST">
    <input type="hidden" name="action" value="CREATE"/>
    <label for="name">Nickname</label> <input type="text" name="name" id="name"/><br/>
    <label for="owner">Owner's email id</label> <input type="text" name="owner" id="owner"/><br/>
    <label for="account">Account</label> <select name="account_id">
        <? foreach ($accounts->accounts() as $account) {
            print "<option value='{$account->id}'>{$account->nickname}</option>";
        } ?></select><br />
    <label for="repository">GitHub repo</label> <input type="text" name="repository" id="repository"/><br/>
    <label for="url">Service URL</label> <input type="text" name="url" id="url"/><br/>
    <input type="Submit" value="Add"/>
    </form>
</section>
</body>
</html>