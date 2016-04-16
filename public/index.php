<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});

session_start();
include "header.php";
?>
<section id="main">
    <h1>Resources</h1>
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
            print "<tr><td><a href='/show_resources.php?service_id={$row->service_id}'>{$row->service_name}</a></td><td>{$row->account_nickname}</td><td>{$row->resource_type}</td><td>{$row->resource_uri}</td><td>{$row->expires}</td></tr>";
        }
        ?>
    </table>
</section>
</body>
</html>