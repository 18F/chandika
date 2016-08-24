<?php
spl_autoload_register(function ($class) {
    include '../lib/' . $class . '.php';
});
ApiKeyAdministrator::authenticate();

$raw_billing_data = json_decode(file_get_contents("php://input"), true);
$month = $raw_billing_data["month"] . "-01";
$provider = $raw_billing_data["provider"];
$costs = $raw_billing_data["costs"];
$totals = $raw_billing_data["totals"];

if (!in_array($provider, AccountAdministrator::providers())) {
    http_response_code(400);
    print "Could not find provider $provider";
    die();
}

$statement = DB::connection()->prepare("DELETE FROM billing WHERE invoice_date = :date AND provider = :provider");
$statement->execute([":date" => $month, ":provider" => $provider]);
$statement = DB::connection()->prepare("INSERT INTO billing (provider, invoice_date, identifier, tagname, tagvalue, amount)
                                        VALUES (:provider, :invoice_date, :identifier, :tagname, :tagvalue, :amount)");
foreach ($costs as $account => $tagged) {
    foreach ($tagged as $tag_name => $values) {
        foreach ($values as $tag_value => $amount) {
            $statement->execute([":invoice_date" => $month, ":provider" => $provider, ":identifier" => $account,
                ":tagname" => $tag_name, ":tagvalue" => $tag_value, ":amount" => round($amount, 2)]);
        }
    }
}

foreach ($totals as $account => $amount) {
    $statement->execute([":invoice_date" => $month, ":provider" => $provider, ":identifier" => $account,
        ":tagname" => null, ":tagvalue" => null, ":amount" => round($amount, 2)]);
}
