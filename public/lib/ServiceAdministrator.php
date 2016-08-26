<?

class ServiceAdministrator
{
    private $auth;
    private $accounts_permitted = [];
    private $is_admin;

    public function __construct($auth)
    {
        $this->auth = $auth;
        $accounts = AccountAdministrator::accounts();
        $this->is_admin = $this->auth->belongsTo(Authenticator::administrator);
        foreach ($accounts as $account) {
            if ($account->is_prod == 0 || $this->is_admin) {
                $this->accounts_permitted[] = $account->id;
            }
        }
    }

    public function services()
    {
        $all_services = DB::query("SELECT s.id, s.name, s.account_id, s.is_archived, s.description, a.label, s.repository, s.url, s.owner, s.billing_code, s.tag, a.is_prod
                  FROM services s LEFT JOIN accounts a ON s.account_id = a.id ORDER BY s.name");
        $services = [];
        foreach ($all_services as $service) {
            if ($service->is_prod == 0 || $this->is_admin) {
                $services[] = $service;
            }
        }
        return $services;
    }

    public function services_filtered($account_selected, $show_archived)
    {
        $filtered = [];
        foreach ($this->services() as $service) {
            $account_checks_out = $account_selected == 0 || $account_selected == $service->account_id;
            $archived_status_checks_out = $show_archived || $service->is_archived == 0;
            if ($account_checks_out && $archived_status_checks_out) {
                $filtered[] = $service;
            }
        }
        return $filtered;
    }

    public function service($id)
    {
        foreach ($this->services() as $service) {
            if ($service->id == $id) return $service;
        }
        return null;
    }

    public function create($properties)
    {
        if (!in_array($properties["account_id"], $this->accounts_permitted)) {
            return;
        }
        $insert = DB::connection()->prepare("INSERT INTO services (name, account_id, repository, owner, url, billing_code, tag, description, is_archived)
                                             VALUES (:name, :account_id, :repository, :owner, :url, :billing_code, :tag, :description, :is_archived)");
        self::helper()->bind($insert, $properties);
        $insert->execute();
    }

    public function update($id, $properties)
    {
        if (!in_array($properties["account_id"], $this->accounts_permitted)) {
            return;
        }
        $update = DB::connection()->prepare("UPDATE services SET name = :name, account_id = :account_id, repository = :repository, owner = :owner, url = :url, billing_code = :billing_code, tag = :tag, is_archived = :is_archived, description = :description WHERE id = :id");
        self::helper()->bind($update, $properties);
        $update->bindParam(":id", $id);
        $update->execute();
    }

    private static function helper()
    {
        $fields = [["name" => "name"], ["name" => "owner"], ["name" => "account_id"], ["name" => "repository"], ["name" => "url"], ["name" => "billing_code"], ["name" => "tag"], ["name" => "is_archived", "type" => CrudHelper::checkbox], ["name" => "description"]];
        return new CrudHelper($fields);
    }
}

?>
