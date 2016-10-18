<?php

class m0005
{
    public function migrate($conn)
    {
        $conn->exec("ALTER TABLE services ADD COLUMN billing_code VARCHAR(255) NOT NULL DEFAULT ''");
        $conn->exec("ALTER TABLE services DROP COLUMN is_billable");
    }
}