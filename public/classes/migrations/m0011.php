<?php

class m0011
{
    public function migrate($conn)
    {
        $conn->exec("ALTER TABLE accounts MODIFY label VARCHAR(127) NOT NULL");
    }
}
