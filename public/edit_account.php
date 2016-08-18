<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

include "header.php";

$account_id = $_REQUEST["account_id"];
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "Update") {
    AccountAdministrator::update($account_id, $_REQUEST["label"], $_REQUEST["provider"], $_REQUEST["identifier"], $_REQUEST["email"], $_REQUEST["description"]);
}
$account = AccountAdministrator::account($account_id);

?>
<section id="main">
    <h1>Edit account</h1>
    <form action="edit_account.php" method="POST">
        <input type="hidden" name="account_id" value="<?= $account_id ?>" />
        <label for="label">Label</label> <input type="text" name="label" id="label" value="<?= $account->label?>"/><br/>
        <label for="provider">Provider</label> <?= Filter::dropdown("provider", AccountAdministrator::providers(), $account->provider) ?><br/>
        <label for="identifier">Identifier</label> <input type="text" name="identifier" id="identifier" value="<?= $account->identifier?>"/><br/>
        <label for="description">Description</label> <input type="text" name="description" id="description" value="<?= $account->description?>"/><br/>
        <label for="email">Notification email</label> <input type="text" name="email" id="email" value="<?= $account->email?>"/><br/>
        <input type="submit" name="action" value="Update"/>
    </form>
</section>
</body>
</html>
