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

    public static function create($properties)
    {
        $insert = DB::connection()->prepare("INSERT INTO accounts (label, provider, identifier, email, description, is_prod) VALUES (:label, :provider, :identifier, :email, :description, :is_prod)");
        self::helper()->bind($insert, $properties);
        $insert->execute();
    }

    public static function update($id, $properties)
    {
        $update = DB::connection()->prepare("UPDATE accounts SET label = :label, provider = :provider, identifier = :identifier, email = :email, description = :description, is_prod = :is_prod WHERE id = :id");
        self::helper()->bind($update, $properties);
        $update->bindParam(":id", $id);
        $update->execute();
    }

    private static function helper()
    {
        $fields = [
            [CrudHelper::name => "label", CrudHelper::desc => "Label"],
            [CrudHelper::name => "provider", CrudHelper::desc => "Provider", CrudHelper::type => CrudHelper::dropdown],
            [CrudHelper::name => "identifier", CrudHelper::desc => "Identifier"],
            [CrudHelper::name => "description", CrudHelper::desc => "Description"],
            [CrudHelper::name => "email", CrudHelper::desc => "Email"],
            [CrudHelper::name => "is_prod", CrudHelper::desc => "Production account", CrudHelper::type => CrudHelper::checkbox]
        ];
        return new CrudHelper($fields);
    }

    public static function form($options, $selected)
    {
        return self::helper()->form($options, $selected);
    }

}
?>