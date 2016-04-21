<?php

class Authenticator
{
    private $user_email;

    public function __construct()
    {
        $oauth = getenv("CHANDIKA_OAUTH");
        if ($oauth != "OFF") {
            session_start();
            if (!isset($_SESSION["user_email"])) {
                header("location: /oauth.php");
                die();
            }
            $this->user_email = $_SESSION["user_email"];
        }
    }

    public function user_email()
    {
        return $this->user_email;
    }
}