<?

class ServiceAdministrator
{
    public static function services()
    {
        return DB::query("SELECT s.id, s.name, s.account_id, s.is_archived, s.description, a.label, s.repository, s.url, s.owner, s.billing_code, s.tag
                  FROM services s LEFT JOIN accounts a ON s.account_id = a.id ORDER BY s.name");
    }

    public static function service($id) {
        foreach (self::services() as $service) {
            if ($service->id == $id) return $service;
        }
        return null;
    }

    public static function create($properties)
    {
        $insert = DB::connection()->prepare("INSERT INTO services (name, account_id, repository, owner, url, billing_code, tag, description, is_archived)
                                             VALUES (:name, :account_id, :repository, :owner, :url, :billing_code, :tag, :description, :is_archived)");
        self::helper()->bind($insert, $properties);
        $insert->execute();
    }

    public static function update($id, $properties)
    {
        $insert = DB::connection()->prepare("UPDATE services SET name = :name, account_id = :account_id, repository = :repository, owner = :owner, url = :url, billing_code = :billing_code, tag = :tag, is_archived = :is_archived, description = :description WHERE id = :id");
        self::helper()->bind($insert, $properties);
        $insert->bindParam(':id', $id);
        $insert->execute();
    }

    private static function helper() {
        $fields = [["name" => "name"], ["name" => "owner"], ["name" => "account_id"], ["name" => "repository"], ["name" => "url"], ["name" => "billing_code"], ["name" => "tag"], ["name" => "is_archived"], ["name" => "description"]];
        return  new CrudHelper($fields);
    }
}

?>
