<?php
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

$service_id = $_REQUEST["service_id"];

$sa = new ServiceAdministrator($auth);
$service = $sa->service($service_id);

$billing_data = BillingAdministrator::byService($service->tag);

include "header.php";
?>
<div class="container-fluid">
    <h1>Billing for service '<?=$service->name?>'</h1>
    Aggregating billing data for all tags with value <?=$service->tag?>.<br/><br/>
    <table class="table-striped">
        <tr>
            <th>Invoice date</th>
            <th>Amount</th>
        </tr>
        <?
        foreach ($billing_data as $invoice_date) {
            print "<tr><td>{$invoice_date->invoice_date}</td><td>{$invoice_date->total}</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>