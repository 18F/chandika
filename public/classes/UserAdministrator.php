<?
class UserAdministrator
{
    public function users()
    {
        $results = [];
        $sql = "SELECT id, email FROM administrators ORDER BY email";
        foreach (DB::connection()->query($sql, PDO::FETCH_OBJ) as $row) {
            $results[] = $row;
        }
        return $results;
    }

    public function create($email)
    {
        $insert = DB::connection()->prepare("INSERT INTO administrators (email) VALUES (:email)");
        $insert->bindParam(':email', $email);
        $insert->execute();
    }

    public function delete($id)
    {
        $delete = DB::connection()->prepare("DELETE FROM administrators WHERE id = :id");
        $delete->bindParam(':id', $id);
        $delete->execute();
    }
}
?>