<?php
require "../autoload.php";
ApiKeyAdministrator::authenticate();

$raw_billing_data = json_decode(file_get_contents("php://input"), true);
$month = $raw_billing_data["invoice_date"];
$provider = $raw_billing_data["provider"];
$tag_notes = $raw_billing_data["tag_notes"];


if (!in_array($provider, AccountAdministrator::providers())) {
    http_response_code(400);
    print "Could not find provider $provider";
    die();
}

$sql = "UPDATE billing SET tagnote = :tagnote WHERE tagvalue = :tagvalue AND invoice_date = :date";
$statement = DB::connection()->prepare($sql);
foreach ($tag_notes as $tagvalue => $tagnote) {
    $statement->execute([":date" => $month, ":tagvalue" => $tagvalue, ":tagnote" => $tagnote]);
}
