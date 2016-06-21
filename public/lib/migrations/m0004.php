<?php

class m0004
{
    public function migrate($conn)
    {
       $conn->exec("ALTER TABLE services ADD COLUMN tag VARCHAR(255) NULL");
    }
}
