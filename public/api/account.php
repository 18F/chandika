<?
spl_autoload_register(function ($class) {
    include '../lib/' . $class . '.php';
});
$account = $_REQUEST["account_id"];

$statement = DB::connection()->prepare("SELECT s.tag, s.id FROM services s JOIN accounts a ON s.account_id = a.id
        WHERE a.identifier = :account");
$statement->execute([":account" => $account]);
$tags = [];
while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
    $tags[$row->id] = $row->tag;
}

$statement = DB::connection()->prepare("SELECT r.uri, r.resource_type, s.id FROM services s JOIN accounts a ON s.account_id = a.id LEFT JOIN resources r ON r.service_id = s.id
        WHERE a.identifier = :account AND (expires IS NULL OR expires > UNIX_TIMESTAMP()) ORDER BY s.id, r.resource_type");
$statement->execute([":account" => $account]);
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
$services[$service_id] = $resources;
$accounts = [];
foreach ($tags as $service_id => $tag) {
    $resources = empty($services[$service_id]) ? [] : $services[$service_id];
    $accounts[] = [ "Tag" => $tag, "Resources" => $resources ];
}
print json_encode($accounts);
?>
