<?php
class DB
{
    private static $conn;

    public static function connection()
    {
        if (self::$conn == null) {
            $cf_config = getenv("VCAP_SERVICES");
            if ($cf_config === false) {
                self::$conn = new PDO("mysql:host=localhost;dbname=scotchbox;charset=utf8", "root", "root"); // assume we're running locally
            } else {
                $cf_config_decoded = json_decode($cf_config, true);
                $connection_string = "mysql:host=".$cf_config_decoded["aws-rds"][0]["credentials"]["host"].";dbname=".$cf_config_decoded["aws-rds"][0]["credentials"]["db_name"];
                self::$conn = new PDO($connection_string, $cf_config_decoded["aws-rds"][0]["credentials"]["username"], $cf_config_decoded["aws-rds"][0]["credentials"]["password"]);
            }
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = self::$conn->query("SHOW TABLES LIKE 'migrations'");
            if ($stmt->rowCount() == 0) self::migrate();
        }
        return self::$conn;
    }

    public static function migrate() {
        self::$conn->exec("CREATE TABLE IF NOT EXISTS migrations (
                          id          INT NOT NULL AUTO_INCREMENT,
                          migration   VARCHAR(10) NOT NULL,
                          PRIMARY KEY(id))");
        $sql = "SELECT migration FROM migrations ORDER BY id DESC";
        $migrated = [];
        foreach (self::$conn->query($sql, PDO::FETCH_OBJ) as $row) {
            $migrated[] = $row->migration;
        }
        $migrations = scandir($_SERVER["DOCUMENT_ROOT"]."/classes/migrations");
        foreach ($migrations as $migration) {
            if (substr($migration, 0, 1) == "m" && !in_array($migration, $migrated)) {
                $classname = substr($migration, 0, -4);
                include "migrations/$migration";
                $migrate = new $classname;
                $migrate->migrate(self::$conn);
                self::$conn->exec("INSERT INTO migrations (migration) VALUES ('$migration')");
            }
        }
    }

    public static function query($query, $params = []) {
        $results = [];
        $stmt = self::$conn->prepare($query);
        $stmt->execute($params);
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }
        return $results;
    }

    public static function execute($query, $params = []) {
        $stmt = self::$conn->prepare($query);
        $stmt->execute($params);
    }
}
?>
