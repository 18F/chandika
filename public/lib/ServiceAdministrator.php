<?

class ServiceAdministrator
{
    public static function services()
    {
        $results = [];
        $sql = "SELECT s.id, s.name, s.account_id, a.nickname, s.repository, s.url, s.owner, s.is_billable, s.tag
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

    public static function create($name, $account_id, $repository, $url, $owner, $is_billable, $tag)
    {
        $insert = DB::connection()->prepare("INSERT INTO services (name, account_id, repository, owner, url, is_billable, tag)
                                             VALUES (:name, :account_id, :repository, :owner, :url, :is_billable, :tag)");
        $insert->bindParam(':name', $name);
        $insert->bindParam(':owner', $owner);
        $insert->bindParam(':account_id', $account_id);
        $insert->bindParam(':repository', $repository);
        $insert->bindParam(':url', $url);
        $insert->bindParam(':is_billable', $is_billable);
        $insert->bindParam(':tag', $tag);
        $insert->execute();
    }

    public static function update($id, $name, $owner, $account_id, $repository, $url, $is_billable, $tag)
    {
        $insert = DB::connection()->prepare("UPDATE services SET name = :name, account_id = :account_id, repository = :repository, owner = :owner, url = :url, is_billable = :is_billable, tag = :tag WHERE id = :id");
        $insert->bindParam(':name', $name);
        $insert->bindParam(':owner', $owner);
        $insert->bindParam(':account_id', $account_id);
        $insert->bindParam(':repository', $repository);
        $insert->bindParam(':is_billable', $is_billable);
        $insert->bindParam(':url', $url);
        $insert->bindParam(':id', $id);
        $insert->bindParam(':tag', $tag);
        $insert->execute();
    }
}

?>
