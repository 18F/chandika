<?php

class BillingAdministrator
{
    public static function months()
    {
        return DB::query("SELECT DISTINCT invoice_date FROM billing ORDER BY invoice_date DESC");
    }

    public static function byMonth($month)
    {
        $query = "SELECT b.identifier, b.amount, a.label, a.description FROM billing b
                  LEFT JOIN accounts a ON b.identifier = a.identifier
                  WHERE b.tagname IS NULL AND b.invoice_date = :invoice_date ORDER BY b.identifier";
        return DB::query($query, [":invoice_date" => $month."-01"]);
    }

    public static function byTag($account_id, $month, $tag_name)
    {
        $query = "SELECT SUM(amount) as total, tagvalue FROM billing
                  WHERE tagname = :tagname AND invoice_date = :invoice_date AND identifier = :account_id
                  GROUP BY tagvalue ORDER BY tagvalue";
        return DB::query($query, [":invoice_date" => $month."-01", ":account_id" => $account_id, ":tagname" => $tag_name]);
    }

    public static function tags($account_id, $month)
    {
        $query = "SELECT DISTINCT tagname FROM billing WHERE invoice_date = :invoice_date AND identifier = :identifier AND tagname IS NOT NULL ORDER BY tagname";
        return DB::query($query, [":identifier" => $account_id, ":invoice_date" => $month."-01"]);
    }

    public static function byService($tag_value)
    {
        $query = "SELECT SUM(amount) as total, invoice_date FROM billing WHERE tagvalue = :tagvalue GROUP BY invoice_date ORDER BY invoice_date DESC";
        return DB::query($query, [":tagvalue" => $tag_value]);
    }
}