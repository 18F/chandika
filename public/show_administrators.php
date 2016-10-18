<?
require "autoload.php";
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "Migrate DB") {
    DB::migrate();
}

include "header.php";
?>
<div class="container-fluid">
    <h1>Administrators</h1>
    <table class="table-striped">
        <tr>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?
        $users = new UserAdministrator();
        foreach ($users->users() as $row) {
            print "<tr><td>{$row->email}</td>
                   <td><a href='add_administrator.php?action=DELETE&id={$row->id}'>Delete</a></td></tr>";
        }
        ?>
    </table>
    <hr/>
    <h2>Add administrator</h2>
    <form action="add_administrator.php" method="POST">
    <input type="hidden" name="action" value="CREATE"/>
    <label for="email">Email</label> <input type="text" name="email" id="email"/><br/>
    <input type="Submit" value="Add"/>
    </form>
    <hr />
    <h2>Migrate DB</h2>
    <p>Run this after upgrading Chandika</p>
    <form action="show_administrators.php"><input type="submit" name="action" value="Migrate DB"/></form>

</div>
</body>
</html>