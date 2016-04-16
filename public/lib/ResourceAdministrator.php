<?
class ResourceAdministrator
{
    private $service_id;
    private $service_name;

    public function __construct($service_id)
    {
        $this->service_id = $service_id;
        $query = DB::connection()->prepare("SELECT name FROM services WHERE id = ?");
        $query->execute(array($service_id));
        $this->service_name = $query->fetch(PDO::FETCH_OBJ)->name;
    }

    public function resources()
    {
        $results = [];
        $sql = "SELECT id, resource_type, owner, uri, created, expires FROM resources WHERE service_id = {$this->service_id} ORDER BY resource_type";
        foreach (DB::connection()->query($sql, PDO::FETCH_OBJ) as $row) {
            $results[] = $row;
        }
        return $results;
    }

    public function name() {
        return $this->service_name;
    }

    public function create($resource_type, $owner, $uri, $expires)
    {
        $insert = DB::connection()->prepare("INSERT INTO resources (service_id, resource_type, owner, uri, expires) VALUES (:service_id, :resource_type, :owner, :uri, :expires)");
        $insert->bindParam(':service_id', $this->service_id);
        $insert->bindParam(':resource_type', $resource_type);
        $insert->bindParam(':owner', $owner);
        $insert->bindParam(':uri', $uri);
        $insert->bindParam(':expires', $expires);
        $insert->execute();
    }

    public function types()
    {
        return [ "AWS resource", "IAA", "HTTPS certificate", "Domain name" ];
    }

    public static function all() {
        $results = [];
        $sql = "SELECT s.id AS service_id, s.name AS service_name, a.nickname AS account_nickname, r.resource_type, r.uri AS resource_uri, r.expires
                  FROM resources r LEFT JOIN services s ON r.service_id = s.id LEFT JOIN accounts a ON s.account_id = a.id";
        foreach (DB::connection()->query($sql, PDO::FETCH_OBJ) as $row) {
            $results[] = $row;
        }
        return $results;
    }
}
?>