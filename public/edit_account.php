<?
require "autoload.php";
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

if (key_exists("action", $_REQUEST)) {
    switch ($_REQUEST["action"]) {
        case "UPDATE":
            AccountAdministrator::update($_REQUEST["account_id"], $_REQUEST);
            break;
        case "DELETE":
            AccountAdministrator::delete($_REQUEST["account_id"]);
            break;
        case "CREATE":
            AccountAdministrator::create($_REQUEST);
            break;
    }
    header("Location: /show_accounts.php");
    die();
}
$account_id = $_REQUEST["account_id"];
$account = AccountAdministrator::account($account_id);
$checked = $account->is_prod == 1 ? " checked" : "";

include "header.php";
?>
<div class="container-fluid">
    <h1>Edit account</h1>
    <form action="edit_account.php" method="POST">
        <input type="hidden" name="account_id" value="<?= $account_id ?>"/>
        <?= AccountAdministrator::form(["provider" => AccountAdministrator::providers()], $account) ?>
        <button type="submit" name="action" value="UPDATE">Update</button>
    </form>
</div>
</body>
</html>
