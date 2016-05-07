<?php

class m0001
{
    public function migrate($conn)
    {
        $conn->exec("CREATE TABLE IF NOT EXISTS administrators (
                          id          INT NOT NULL AUTO_INCREMENT,
                          email       VARCHAR(50) NOT NULL,
                          created     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY(id))");
        $conn->exec("CREATE TABLE IF NOT EXISTS accounts (
                          id          INT NOT NULL AUTO_INCREMENT,
                          nickname    VARCHAR(50) NOT NULL,
                          provider    VARCHAR(50) NOT NULL,
                          identifier  VARCHAR(255) NOT NULL,
                          is_prod     TINYINT NOT NULL DEFAULT 0,
                          created     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY(id))");
        $conn->exec("CREATE TABLE IF NOT EXISTS services (
                          id          INT NOT NULL AUTO_INCREMENT,
                          name        VARCHAR(255) NOT NULL,
                          account_id  INT NOT NULL,
                          repository  VARCHAR(255) NOT NULL,
                          url         VARCHAR(255) NOT NULL,
                          owner       VARCHAR(255) NOT NULL,
                          verified    INT NULL,
                          created     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY(id))");
        $conn->exec("CREATE TABLE IF NOT EXISTS resources (
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