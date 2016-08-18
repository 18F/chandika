<?

class ServiceAdministrator
{
    public static function services()
    {
        $results = [];
        $sql = "SELECT s.id, s.name, s.account_id, a.label, s.repository, s.url, s.owner, s.billing_code, s.tag
                  FROM services s LEFT JOIN accounts a ON s.account_id = a.id ORDER BY s.name";
        foreach (DB::connection()->query($sql, PDO::FETCH_OBJ) as $row) {
            $results[] = $row;
        }
        return $results;
    }

    public static function service($id) {
        foreach (self::services() as $service) {
            if ($service->id == $id) return $service;
        }
        return null;
    }

    public static function create($name, $account_id, $repository, $url, $owner, $billing_code, $tag)
    {
        $insert = DB::connection()->prepare("INSERT INTO services (name, account_id, repository, owner, url, billing_code, tag)
                                             VALUES (:name, :account_id, :repository, :owner, :url, :billing_code, :tag)");
        $insert->bindParam(':name', $name);
        $insert->bindParam(':owner', $owner);
        $insert->bindParam(':account_id', $account_id);
        $insert->bindParam(':repository', $repository);
        $insert->bindParam(':url', $url);
        $insert->bindParam(':billing_code', $billing_code);
        $insert->bindParam(':tag', $tag);
        $insert->execute();
    }

    public static function update($id, $name, $owner, $account_id, $repository, $url, $billing_code, $tag)
    {
        $insert = DB::connection()->prepare("UPDATE services SET name = :name, account_id = :account_id, repository = :repository, owner = :owner, url = :url, billing_code = :billing_code, tag = :tag WHERE id = :id");
        $insert->bindParam(':name', $name);
        $insert->bindParam(':owner', $owner);
        $insert->bindParam(':account_id', $account_id);
        $insert->bindParam(':repository', $repository);
        $insert->bindParam(':billing_code', $billing_code);
        $insert->bindParam(':url', $url);
        $insert->bindParam(':id', $id);
        $insert->bindParam(':tag', $tag);
        $insert->execute();
    }
}

?>
