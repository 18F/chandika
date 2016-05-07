<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

include "header.php";
?>
<section id="main">
    <h1>Accounts</h1>
    <table class="table-striped">
        <tr>
            <th>Label</th>
            <th>Provider</th>
            <th>Identifier</th>
            <th>Notification email</th>
            <th>Actions</th>
        </tr>
        <?
        foreach (AccountAdministrator::accounts() as $row) {
            print "<tr><td>{$row->nickname}</td><td>{$row->provider}</td><td>{$row->identifier}</td><td>{$row->email}</td><td><a href='edit_account.php?account_id={$row->id}'>Edit</a></td></tr>";
        }
        ?>
    </table>
    <hr/>
    <h2>Add account</h2>
    <form action="add_account.php" method="POST">
        <input type="hidden" name="action" value="CREATE"/>
        <label for="nickname">Label</label> <input type="text" name="nickname" id="nickname"/><br/>
        <label for="provider">Provider</label> <?= Select::render("provider", AccountAdministrator::providers(), 0) ?><br/>
        <label for="identifier">Identifier</label> <input type="text" name="identifier" id="identifier"/><br/>
        <label for="email">Notification email</label> <input type="text" name="email" id="email"/><br/>
        <input type="Submit" value="Add"/>
    </form>
</section>
</body>
</html>