<?
class AccountAdministrator
{
    public static function providers()
    {
        return [ "0" => "Amazon AWS" ];
    }

    public static function accounts()
    {
        $results = [];
        $sql = "SELECT id, nickname, provider, identifier, email FROM accounts ORDER BY nickname";
        foreach (DB::connection()->query($sql, PDO::FETCH_OBJ) as $row) {
            $results[] = $row;
        }
        return $results;
    }

    public static function account($id) {
        foreach (self::accounts() as $account) {
            if ($account->id == $id) return $account;
        }
        return null;
    }

    public static function create($nickname, $provider, $identifier, $email)
    {
        $insert = DB::connection()->prepare("INSERT INTO accounts (nickname, provider, identifier, email) VALUES (:nickname, :provider, :identifier, :email)");
        $insert->bindParam(':nickname', $nickname);
        $insert->bindParam(':provider', $provider);
        $insert->bindParam(':identifier', $identifier);
        $insert->bindParam(':email', $email);
        $insert->execute();
    }

    public static function update($id, $nickname, $provider, $identifier, $email)
    {
        $insert = DB::connection()->prepare("UPDATE accounts SET nickname = :nickname, provider = :provider, identifier = :identifier, email = :email WHERE id = :id");
        $insert->bindParam(':nickname', $nickname);
        $insert->bindParam(':provider', $provider);
        $insert->bindParam(':identifier', $identifier);
        $insert->bindParam(':email', $email);
        $insert->bindParam(':id', $id);
        $insert->execute();
    }
}
?>