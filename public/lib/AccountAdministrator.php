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
        $sql = "SELECT id, label, provider, identifier, description, email FROM accounts ORDER BY label";
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

    public static function create($label, $provider, $identifier, $email, $description)
    {
        $insert = DB::connection()->prepare("INSERT INTO accounts (label, provider, identifier, email, description) VALUES (:label, :provider, :identifier, :email, :description)");
        $insert->bindParam(':label', $label);
        $insert->bindParam(':provider', $provider);
        $insert->bindParam(':identifier', $identifier);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':email', $email);
        $insert->execute();
    }

    public static function update($id, $label, $provider, $identifier, $email, $description)
    {
        $insert = DB::connection()->prepare("UPDATE accounts SET label = :label, provider = :provider, identifier = :identifier, email = :email, description = :description WHERE id = :id");
        $insert->bindParam(':label', $label);
        $insert->bindParam(':provider', $provider);
        $insert->bindParam(':identifier', $identifier);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':email', $email);
        $insert->bindParam(':id', $id);
        $insert->execute();
    }
}
?>