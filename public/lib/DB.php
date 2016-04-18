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
            self::createSchema();
        }
        return self::$conn;
    }

    private static function createSchema() {
        self::$conn->exec("CREATE TABLE IF NOT EXISTS administrators (
                          id          INT NOT NULL AUTO_INCREMENT,
                          email       VARCHAR(50) NOT NULL,
                          created     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY(id))");
        self::$conn->exec("CREATE TABLE IF NOT EXISTS accounts (
                          id          INT NOT NULL AUTO_INCREMENT,
                          nickname    VARCHAR(50) NOT NULL,
                          provider    VARCHAR(50) NOT NULL,
                          identifier  VARCHAR(255) NOT NULL,
                          is_prod     TINYINT NOT NULL DEFAULT 0,
                          created     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY(id))");
        self::$conn->exec("CREATE TABLE IF NOT EXISTS services (
                          id          INT NOT NULL AUTO_INCREMENT,
                          name        VARCHAR(255) NOT NULL,
                          account_id  INT NOT NULL,
                          repository  VARCHAR(255) NOT NULL,
                          url         VARCHAR(255) NOT NULL,
                          created     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY(id))");
        self::$conn->exec("CREATE TABLE IF NOT EXISTS resources (
                          id          INT NOT NULL AUTO_INCREMENT,
                          service_id  INT NOT NULL,
                          resource_type        VARCHAR(50) NOT NULL,
                          owner       VARCHAR(255) NOT NULL,
                          uri         VARCHAR(50) NOT NULL,
                          created     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          expires     INT NULL,
                          PRIMARY KEY(id))");
    }
}
?>
