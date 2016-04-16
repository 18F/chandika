<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});

session_start();
include "header.php";
?>
<section id="main">
    <h1>Accounts</h1>
    <table class="table-striped">
        <tr>
            <th>Nickname</th>
            <th>Provider</th>
            <th>Identifier</th>
        </tr>
        <?
        $accounts = new AccountAdministrator();
        foreach ($accounts->accounts() as $row) {
            print "<tr><td>{$row->nickname}</td><td>{$row->provider}</td><td>{$row->identifier}</td><td></tr>";
        }
        ?>
    </table>
    <hr/>
    <h2>Add account</h2>
    <form action="add_account.php" method="POST"/>
    <input type="hidden" name="action" value="CREATE"/>
    <label for="nickname">Nickname</label> <input type="text" name="nickname" id="nickname"/><br/>
    <label for="provider">Provider</label> <select name="provider">
        <? foreach ($accounts->providers() as $name) {
            print "<option value='$name'>$name</option>";
        } ?></select><br />
    <label for="identifier">Identifier</label> <input type="text" name="identifier" id="identifier"/><br/>
    <input type="Submit" value="Add"/>
    </form>
</section>
</body>
</html>