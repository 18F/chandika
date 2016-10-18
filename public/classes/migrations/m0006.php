<?php

class m0006
{
    public function migrate($conn)
    {
        $conn->exec("ALTER TABLE accounts CHANGE COLUMN nickname label VARCHAR(50) NOT NULL");
        $conn->exec("ALTER TABLE accounts ADD COLUMN description VARCHAR(255) NULL");
        $conn->exec("CREATE TABLE IF NOT EXISTS billing (
                          id           INT NOT NULL AUTO_INCREMENT,
                          provider     VARCHAR(50) NOT NULL,
                          invoice_date DATE NOT NULL,
                          identifier   VARCHAR(255) NOT NULL,
                          tagname      VARCHAR(50) NULL,
                          tagvalue     VARCHAR(50) NULL,
                          amount       DECIMAL (10,2),
                          PRIMARY KEY(id))");
    }
}