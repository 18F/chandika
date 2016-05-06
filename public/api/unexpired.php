<?
spl_autoload_register(function ($class) {
    include 'lib/' . $class . '.php';
});
$account = $_REQUEST["account"];
$type = $_REQUEST["type"];
$statement = DB::connection()->prepare("SELECT uri FROM resources r JOIN services s ON r.service_id = s.id JOIN accounts a ON s.account_id = a.id
        WHERE r.resource_type = :resource_type AND a.identifier = :account AND (expires IS NULL OR expires > UNIX_TIMESTAMP())");
$statement->execute([":resource_type" => $type, ":account" => $account]);
while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
    print $row->uri;
}
?>
