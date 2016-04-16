<?
class AccountAdministrator
{
    public function providers()
    {
        return [ "Amazon AWS" ];
    }

    public function accounts()
    {
        $results = [];
        $sql = "SELECT id, nickname, provider, identifier FROM accounts ORDER BY nickname";
        foreach (DB::connection()->query($sql, PDO::FETCH_OBJ) as $row) {
            $results[] = $row;
        }
        return $results;
    }

    public function create($nickname, $provider, $identifier)
    {
        $insert = DB::connection()->prepare("INSERT INTO accounts (nickname, provider, identifier) VALUES (:nickname, :provider, :identifier)");
        $insert->bindParam(':nickname', $nickname);
        $insert->bindParam(':provider', $provider);
        $insert->bindParam(':identifier', $identifier);
        $insert->execute();
    }
}
?>