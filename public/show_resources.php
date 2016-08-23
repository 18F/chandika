<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

$service_id = $_REQUEST["service_id"];
$resources = new ResourceAdministrator($service_id);
include "header.php";
?>
<div class="container-fluid">
    <h1>Resources for service <?= $resources->name()?></h1>
    <table class="table-striped">
        <tr>
            <th>Type</th>
            <th>Owner</th>
            <th>URI</th>
            <th>Created</th>
            <th>Expires</th>
            <th>Action</th>
        </tr>
        <?
        foreach ($resources->resources() as $row) {
            $expiry = gmdate("Y-m-d", $row->expires);
            print "<tr><td>{$row->resource_type}</td><td>{$row->owner}</td><td>{$row->uri}</td><td>{$row->created}</td><td>$expiry</td><td><a href='add_resource.php?action=DELETE&id={$row->id}&service_id=$service_id'>Delete</a></td></tr>";
        }
        ?>
    </table>
    <hr/>
    <h2>Add resource</h2>
    <p>For AWS resources, please use the <a href="http://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html">ARN</a> as the URL.
    The account id for this service is '<?= $resources->account_identifier()?>'</p>
    <form action="add_resource.php" method="POST">
    <input type="hidden" name="action" value="CREATE"/>
    <input type="hidden" name="service_id" value="<?= $service_id?>"/>
    <label for="resource_type">Resource type</label> <select name="resource_type">
        <? foreach ($resources->types() as $type) {
            print "<option value='{$type}'>{$type}</option>";
        } ?></select><br />
    <label for="uri">URI</label> <input type="text" name="uri" id="uri"/><br/>
    <input type="Submit" value="Add"/>
    </form>
</div>
</body>
</html>