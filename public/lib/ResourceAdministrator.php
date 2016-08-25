<?
class ResourceAdministrator
{
    private $service_id;
    private $service_info;

    private static $types_with_expiry = [ "AWS resource" => [ "prod" => 365, "non-prod" => 30 ], "IAA" => [], "HTTPS certificate" => [], "Domain name" => [], "ATO" => [] ];

    public function __construct($service_id)
    {
        $this->service_id = $service_id;
        $services = DB::query("SELECT s.name, a.identifier, a.is_prod FROM services s LEFT JOIN accounts a ON s.account_id = a.id WHERE s.id = ?", [$service_id]);
        $this->service_info = $services[0];
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

    private function get_expiry_date($resource_type, $expires) {
        if (!array_key_exists($resource_type, self::$types_with_expiry)) throw new Exception("Couldn't find type ".$resource_type);
        $expiry_data = self::$types_with_expiry[$resource_type];
        if (empty($expiry_data)) return $expires;
        $days_to_add = $this->service_info->is_prod ? $expiry_data["prod"] : $expiry_data["non-prod"];
        return time() + (24 * 60 * 60 * $days_to_add);
    }

    public function create($resource_type, $owner, $uri, $expires)
    {
        $insert = DB::connection()->prepare("INSERT INTO resources (service_id, resource_type, owner, uri, expires) VALUES (:service_id, :resource_type, :owner, :uri, :expires)");
        $insert->bindParam(':service_id', $this->service_id);
        $insert->bindParam(':resource_type', $resource_type);
        $insert->bindParam(':owner', $owner);
        $insert->bindParam(':uri', $uri);
        $expiry_date = $this->get_expiry_date($resource_type, $expires);
        $insert->bindParam(':expires', $expiry_date);
        $insert->execute();
    }

    public static function delete($id)
    {
      $delete = DB::connection()->prepare("DELETE FROM resources WHERE id = :id");
      $delete->bindParam(':id', $id);
      $delete->execute();
    }

    public static function types()
    {
        return array_keys(self::$types_with_expiry);
    }

    public static function all($expiry_days, $service_id, $resource_type) {
        $results = [];
        $sql = "SELECT s.id AS service_id, s.name AS service_name, a.label AS account_label, r.resource_type, r.uri AS resource_uri, r.expires
                  FROM resources r LEFT JOIN services s ON r.service_id = s.id LEFT JOIN accounts a ON s.account_id = a.id WHERE (r.expires > UNIX_TIMESTAMP() OR r.expires IS NULL)";
        $where = new WhereConstructor(false);
        $where->addParam("(r.expires IS NULL OR r.expires < (UNIX_TIMESTAMP() + :days))", ":days", $expiry_days * 3600 * 24);
        $where->addParam("s.id = :service_id", ":service_id", $service_id);
        $where->addParam("r.resource_type = :resource_type", ":resource_type", $resource_type);
        $statement = DB::connection()->prepare($sql.$where->where());
        $statement->execute($where->params());
        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }
        return $results;
    }

    public function name() {
        return $this->service_info->name;
    }

    public function account_identifier()
    {
        return $this->service_info->identifier;
    }

}

class WhereConstructor {

    private $params = [];
    private $where_clause;

    public function __construct($include_where)
    {
        $this->where_clause = $include_where ? " WHERE " : " AND ";
    }


    public function addParam($where, $param_name, $param_value) {
        if (!empty($param_value)) {
            if (!empty($this->params)) $this->where_clause .= " AND ";
            $this->where_clause .= $where;
            $this->params[$param_name] = $param_value;
        }
    }

    public function where() {
        if (empty($this->params)) return "";
        return $this->where_clause;
    }

    public function params() {
        return $this->params;
    }
}
?>