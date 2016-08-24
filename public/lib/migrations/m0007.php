<?php

class m0007
{
    public function migrate($conn)
    {
        $conn->exec("CREATE TABLE IF NOT EXISTS api_keys (
                          id           INT NOT NULL AUTO_INCREMENT,
                          label        VARCHAR(50) NOT NULL,
                          last_used    DATETIME NULL,
                          active       TINYINT NOT NULL DEFAULT 1,
                          uuid         VARCHAR(50) NOT NULL,
                          PRIMARY KEY(id))");
    }
}