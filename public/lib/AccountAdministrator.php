<?
class AccountAdministrator
{
    public static function providers()
    {
        return [ "0" => "Amazon AWS" ];
    }

    public static function accounts()
    {
        return DB::query("SELECT id, label, provider, identifier, description, email, is_prod FROM accounts ORDER BY label");
    }

    public static function account($id) {
        foreach (self::accounts() as $account) {
            if ($account->id == $id) return $account;
        }
        return null;
    }

    public static function create($label, $provider, $identifier, $email, $description, $is_prod)
    {
        $insert = DB::connection()->prepare("INSERT INTO accounts (label, provider, identifier, email, description, is_prod) VALUES (:label, :provider, :identifier, :email, :description, :is_prod)");
        $insert->bindParam(':label', $label);
        $insert->bindParam(':provider', $provider);
        $insert->bindParam(':identifier', $identifier);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':email', $email);
        $insert->bindParam(':is_prod', $is_prod);
        $insert->execute();
    }

    public static function update($id, $label, $provider, $identifier, $email, $description, $is_prod)
    {
        $insert = DB::connection()->prepare("UPDATE accounts SET label = :label, provider = :provider, identifier = :identifier, email = :email, description = :description, is_prod = :is_prod WHERE id = :id");
        $insert->bindParam(':label', $label);
        $insert->bindParam(':provider', $provider);
        $insert->bindParam(':identifier', $identifier);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':email', $email);
        $insert->bindParam(':is_prod', $is_prod);
        $insert->bindParam(':id', $id);
        $insert->execute();
    }
}
?>