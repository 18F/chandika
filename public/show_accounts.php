<?
require "autoload.php";
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

include "header.php";
?>
<div class="container-fluid">
    <h1>Accounts</h1>
    <table class="table-striped">
        <tr>
            <th>Label</th>
            <th>Provider</th>
            <th>Identifier</th>
            <th>Notification email</th>
            <th>Description</th>
            <th>Production</th>
            <th>Archived</th>
            <th>Actions</th>
        </tr>
        <?
        foreach (AccountAdministrator::accounts() as $row) {
            $provider = AccountAdministrator::providers()[$row->provider];
            $prod = $row->is_prod ? "Yes" : "No";
            $archived = $row->is_archived ? "Yes" : "No";
            print "<tr><td>{$row->label}</td><td>$provider</td><td>{$row->identifier}</td><td>{$row->email}</td><td>{$row->description}</td><td>$prod</td><td>$archived</td><td><a href='edit_account.php?account_id={$row->id}'>Edit</a> | <a href='edit_account.php?action=DELETE&account_id={$row->id}'>Delete</a></td></tr>";
        }
        ?>
    </table>
    <hr/>
    <h2>Add account</h2>
    <form action="edit_account.php" method="POST">
        <?= AccountAdministrator::form(["provider" => AccountAdministrator::providers()], []) ?>
        <button type="submit" name="action" value="CREATE">Add</button>
    </form>
</div>
</body>
</html>