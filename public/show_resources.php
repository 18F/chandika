<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
session_start();
$service_id = $_REQUEST["service_id"];
$resources = new ResourceAdministrator($service_id);
include "header.php";
?>
<section id="main">
    <h1>Resources for service <?= $resources->name()?></h1>
    <table class="table-striped">
        <tr>
            <th>Type</th>
            <th>Owner</th>
            <th>URI</th>
            <th>Created</th>
            <th>Expires</th>
        </tr>
        <?
        foreach ($resources->resources() as $row) {
            print "<tr><td>{$row->resource_type}</td><td>{$row->owner}</td><td>{$row->uri}</td><td>{$row->created}</td><td>{$row->expires}</td></tr>";
        }
        ?>
    </table>
    <hr/>
    <h2>Add resource</h2>
    <form action="add_resource.php" method="POST"/>
    <input type="hidden" name="action" value="CREATE"/>
    <input type="hidden" name="service_id" value="<?= $service_id?>"/>
    <label for="resource_type">Resource type</label> <select name="resource_type">
        <? foreach ($resources->types() as $type) {
            print "<option value='{$type}'>{$type}</option>";
        } ?></select><br />
    <label for="uri">URI</label> <input type="text" name="uri" id="uri"/><br/>
    <input type="Submit" value="Add"/>
    </form>
</section>
</body>
</html>