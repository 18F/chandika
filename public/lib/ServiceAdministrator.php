<?
class ServiceAdministrator
{
    public function services()
    {
        $results = [];
        $sql = "SELECT s.id, s.name, s.account_id, a.nickname, s.repository, s.url
                  FROM services s LEFT JOIN accounts a ON s.account_id = a.id ORDER BY s.name";
        foreach (DB::connection()->query($sql, PDO::FETCH_OBJ) as $row) {
            $results[] = $row;
        }
        return $results;
    }

    public function create($name, $account_id, $repository, $url)
    {
        $insert = DB::connection()->prepare("INSERT INTO services (name, account_id, repository, url) VALUES (:name, :account_id, :repository, :url)");
        $insert->bindParam(':name', $name);
        $insert->bindParam(':account_id', $account_id);
        $insert->bindParam(':repository', $repository);
        $insert->bindParam(':url', $url);
        $insert->execute();
    }
}
?>