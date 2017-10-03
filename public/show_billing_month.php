<?php
require "autoload.php";
$auth = new Authenticator();

$account_id = $_REQUEST["account_id"];
$invoice_date = $_REQUEST["invoice_date"];

$tag_names = [];
@array_walk(BillingAdministrator::tags($account_id, $invoice_date), function ($value, $key) use (&$tag_names) {
    $tag_names[$value->tagname] = $value->tagname;
});

$selected_tag = key_exists("tag_name", $_REQUEST) ? $_REQUEST["tag_name"] : array_keys($tag_names)[0];

$billing_data = BillingAdministrator::byTag($account_id, $invoice_date, $selected_tag);

include "header.php";
?>
<div class="container-fluid">
    <h1>Billing by tag</h1>
    <form action="show_billing_month.php" method="get">
        <strong>Account:</strong> <?= $account_id ?>
        <strong>Invoice date:</strong> <?= $invoice_date ?>
        <strong>Available tags:</strong> <?= Filter::dropdown("tag_name", $tag_names, $selected_tag) ?>
        <input type="hidden" name="account_id" value="<?=$account_id?>"/>
        <input type="hidden" name="invoice_date" value="<?=$invoice_date?>"/>
        <button type="submit" name="action" value="filter">Go</button>
    </form><hr/>
    <table class="table-striped">
        <tr>
            <th>Tag value</th>
            <th>Amount</th>
        </tr>
        <?
        foreach ($billing_data as $account) {
            print "<tr><td title=\"{$account->tagnote}\">{$account->tagvalue}</td><td>".money_format('%(#10n', $account->total)."</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>