<?php
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$auth = new Authenticator();

$months = [];
@array_walk(BillingAdministrator::months(), function ($value, $key) use (&$months) {
    $month = substr($value->invoice_date, 0, 7);
    $months[$month] = $month;
});
$selected_month = key_exists("month", $_REQUEST) ? $_REQUEST["month"] : array_keys($months)[0];
$billing_data = BillingAdministrator::byMonth($selected_month);

include "header.php";
?>
<div class="container-fluid">
    <h1>Billing</h1>
    Available months: <?= Filter::dropdown("month", $months, $selected_month) ?>
    <h2>Total spend for month <?= $selected_month ?></h2>
    <table class="table-striped">
        <tr>
            <th>Account id</th>
            <th>Label</th>
            <th>Amount</th>
            <th>Description</th>
        </tr>
        <?
        foreach ($billing_data as $account) {
            print "<tr><td>{$account->identifier}</td><td>{$account->label}</td><td>{$account->amount}</td><td>{$account->description}</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>