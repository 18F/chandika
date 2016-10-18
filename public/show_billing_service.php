<?php
require "autoload.php";
$auth = new Authenticator();

$service_id = $_REQUEST["service_id"];

$sa = new ServiceAdministrator($auth);
$service = $sa->service($service_id);
include "header.php";
?>
<div class="container-fluid">
    <h1>Billing for service '<?= $service->name ?>'</h1>
    <?
    if (empty($service->tag)) {
        print "This service has no infrastructure tag associated with it.";
    } else {

    $billing_data = BillingAdministrator::byService($service->tag);

    ?>
    Aggregating billing data for all tags with value <?= $service->tag ?>.<br/><br/>
    <table class="table-striped">
        <tr>
            <th>Invoice date</th>
            <th>Amount</th>
        </tr>
        <?
        foreach ($billing_data as $line) {
            print "<tr><td>{$line->invoice_date}</td><td>" . money_format('%(#10n', $line->total) . "</td></tr>";
        }
        ?>
    </table>
</div>
<?
}
?>
</body>
</html>