<?php
require "autoload.php";
$auth = new Authenticator();

$months = [];
@array_walk(BillingAdministrator::months(), function ($value, $key) use (&$months) {
    $months[$value->invoice_date] = $value->invoice_date;
});
if (count($months) > 0) {
    $selected_date = key_exists("month", $_REQUEST) ? $_REQUEST["month"] : array_keys($months)[0];
    $billing_data = BillingAdministrator::byInvoiceDate($selected_date);
}

include "header.php";
?>
<div class="container-fluid">
    <h1>Billing</h1>
<?if (count($months) == 0) {?>
    Chandika has no billing data available.
    To load billing data into Chandika from Amazon AWS, set up "Detailed Billing Report with Resources and Tags"
    as described <a href="http://docs.aws.amazon.com/awsaccountbilling/latest/aboutv2/detailed-billing-reports.html">here</a>.
    Once you have downloaded the csv file, you can import it into Chandika
    using <a href="https://github.com/18F/chandika/blob/master/scripts/billing.py">this script</a>.
<?} else {?>
    <form action="show_billing.php" method="get">
        Available invoice dates: <?= Filter::dropdown("month", $months, $selected_date) ?>
        <button type="submit" name="action" value="filter">Go</button>
    </form>
    <h2>Total spend for invoice date: <?= $selected_date ?></h2>
    <table class="table-striped">
        <tr>
            <th>Account id</th>
            <th>Label</th>
            <th>Amount</th>
            <th>Description</th>
        </tr>
        <?
        foreach ($billing_data as $account) {
            print "<tr><td><a href='show_billing_month.php?account_id={$account->identifier}&invoice_date={$selected_date}'>{$account->identifier}</a></td><td>{$account->label}</td><td>".money_format('%(#10n', $account->amount)."</td><td>{$account->description}</td></tr>";
        }
        ?>
    </table>
<?}?>
</div>
</body>
</html>