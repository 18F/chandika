<?php

class m0010
{
    public function migrate($conn)
    {
        $conn->exec("ALTER TABLE billing MODIFY tagname VARCHAR(127)");
        $conn->exec("ALTER TABLE billing MODIFY tagvalue VARCHAR(127)");
        $conn->exec("ALTER TABLE billing ADD COLUMN tagnote VARCHAR(127) NULL");
    }
}
