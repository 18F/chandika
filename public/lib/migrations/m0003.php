<?php

class m0003
{
    public function migrate($conn)
    {
        $conn->exec("ALTER TABLE resources MODIFY COLUMN uri uri VARCHAR(255) NOT NULL,");
    }
}
