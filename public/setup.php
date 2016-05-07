<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();
$auth->assertRole(Authenticator::administrator);

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "Migrate DB") {
    DB::migrate();
}

include "header.php";
?>
<section id="main">
    <form action="setup.php"><input type="submit" name="action" value="Migrate DB"/></form>
</section>
</body>
</html>