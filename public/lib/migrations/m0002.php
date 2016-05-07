<?php

class m0002
{
    public function migrate($conn)
    {
        $conn->exec("ALTER TABLE accounts ADD COLUMN email VARCHAR(255) NOT NULL DEFAULT ''");
        $conn->exec("ALTER TABLE services ADD COLUMN is_billable TINYINT NOT NULL DEFAULT 0");
    }
}