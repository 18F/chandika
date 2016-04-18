<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});

session_start();
include "header.php";
$filter = new Filter();
$expiry_selected = isset($_REQUEST["expiry"]) ? $_REQUEST["expiry"] : "7";
$service_selected = isset($_REQUEST["service"]) ? $_REQUEST["service"] : "";
$resource_type_selected = isset($_REQUEST["resource_type_selected"]) ? $_REQUEST["resource_type_selected"] : "";
$service_admin = new ServiceAdministrator();
$services = ["" => "all"];
foreach ($service_admin->services() as $service) {
    $services[$service->id] = $service->name;
}
$resource_types = ["" => "all"];
foreach (ResourceAdministrator::types() as $resource) {
    $resource_types[$resource] = $resource;
}
?>
<section id="main">
    <h1>Resources</h1>
    <form action="index.php" method="get">
        Filter by:
        <em>expiry date: <?= $filter->dropdown("expiry", ["7" => "7 days", "30" => "30 days", "365" => "1 year", "" => "all"], $expiry_selected)?></em>
        <em>service: <?= $filter->dropdown("service", $services, $service_selected)?></em>
        <em>resource type: <?= $filter->dropdown("resource_type", $resource_types, $resource_type_selected)?></em>
        <input type="submit" value="Filter">
    </form>
    <table class="table-striped">
        <tr>
            <th>Service</th>
            <th>Account</th>
            <th>Resource type</th>
            <th>URI</th>
            <th>Expiry date</th>
        </tr>
        <?
        foreach (ResourceAdministrator::all() as $row) {
            $expiry = gmdate("Y-m-d", $row->expires);
            print "<tr><td><a href='/show_resources.php?service_id={$row->service_id}'>{$row->service_name}</a></td><td>{$row->account_nickname}</td><td>{$row->resource_type}</td><td>{$row->resource_uri}</td><td>$expiry</td></tr>";
        }
        ?>
    </table>
</section>
</body>
</html>