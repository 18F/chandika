<?
class ApiKeyAdministrator
{
    private static function uuid()
    {
        $uuid = hash("sha1", openssl_random_pseudo_bytes(16));
        $uuid[12] = 5;
        $uuid[16] = dechex(+"0x$uuid[16]" & 3 | 8);
        return substr(preg_replace('/^.{8}|.{4}/', '\0-', $uuid, 4), 0, 36);
    }

    public static function apiKeys()
    {
        return DB::query("SELECT id, label, last_used, uuid FROM api_keys");
    }

    public static function create($label)
    {
        $uuid = self::uuid();
        DB::execute("INSERT INTO api_keys (uuid, label) VALUES (:uuid, :label)", [":uuid" => $uuid, ":label" => $label]);
        return $uuid;
    }

    public static function delete($id)
    {
        DB::execute("DELETE FROM api_keys WHERE id = :id", [":id" => $id]);
    }

    public static function authenticate()
    {
        if (!key_exists("api_key", $_REQUEST)) {
            die();
        }
        $sql = "UPDATE api_keys SET last_used = NOW() WHERE uuid = :uuid";
        $stmt = DB::connection()->prepare($sql);
        $stmt->execute([":uuid" => $_REQUEST["api_key"]]);
        if ($stmt->rowCount() != 1) {
            die();
        }
    }
}
?>