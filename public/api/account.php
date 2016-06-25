<?
spl_autoload_register(function ($class) {
    include '../lib/' . $class . '.php';
});
$account_id = $_REQUEST["account_id"];

$statement = DB::connection()->prepare("SELECT nickname, is_prod, provider FROM accounts WHERE identifier = :account");
$statement->execute([":account" => $account_id]);
$row = $statement->fetch(PDO::FETCH_OBJ);
$account["Name"] = $row->nickname;
$account["Production"] = $row->is_prod ? "1" : "0";
$account["Provider"] = $row->provider;

$statement = DB::connection()->prepare("SELECT s.tag, s.id, s.name FROM services s JOIN accounts a ON s.account_id = a.id
        WHERE a.identifier = :account");
$statement->execute([":account" => $account_id]);
$tags = [];
$names = [];
while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
    $tags[$row->id] = $row->tag;
    $names[$row->id] = $row->name;
}

$statement = DB::connection()->prepare("SELECT r.uri, r.resource_type, s.id FROM services s JOIN accounts a ON s.account_id = a.id JOIN resources r ON r.service_id = s.id
        WHERE a.identifier = :account AND (expires IS NULL OR expires > UNIX_TIMESTAMP()) ORDER BY s.id, r.resource_type");
$statement->execute([":account" => $account_id]);
$services = [];
$service_id = 0;

while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
    if ($row->id != $service_id) {
        if ($service_id != 0) {
            $services[$service_id] = $resources;
        }
        $service_id = $row->id;
        $resources = [];
    }
    if (empty($resources[$row->resource_type])) {
        $resources[$row->resource_type] = [];
    }
    $resources[$row->resource_type][] = $row->uri;
}
if ($statement->rowCount() > 0) {
    $services[$service_id] = $resources;
}
$service_json = [];
foreach ($tags as $service_id => $tag) {
    $resources = !array_key_exists($service_id, $services) || empty($services[$service_id]) ? [] : $services[$service_id];
    $service_json[] = [ "Name" => $names[$service_id], "Tag" => $tag, "Resources" => $resources ];
}
$account["Services"] = $service_json;
print json_encode($account);
?>
