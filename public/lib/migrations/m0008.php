<?php

class m0008
{
    public function migrate($conn)
    {
        $conn->exec("ALTER TABLE billing ADD COLUMN discount_factor DECIMAL(10,8) NOT NULL DEFAULT 1");
    }
}