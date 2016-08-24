<?php

class BillingAdministrator
{
    public static function months()
    {
        return DB::query("SELECT DISTINCT invoice_date FROM billing");
    }

    public static function byMonth($month)
    {
        $query = "SELECT b.identifier, b.amount, a.label, a.description FROM billing b
                  LEFT JOIN accounts a ON b.identifier = a.identifier
                  WHERE b.tagname IS NULL AND b.invoice_date = :invoice_date";
        return DB::query($query, [":invoice_date" => $month."-01"]);
    }
}