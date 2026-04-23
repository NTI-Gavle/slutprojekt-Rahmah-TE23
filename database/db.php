<?php
function getDB() {
    static $db = null;
    if ($db === null) {
        try {
            $host = 'localhost';
            $dbname = 'kvitter';
            $user = 'root';
            $pass = 'Root';
            $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Databasfel: " . $e->getMessage());
        }
    }
    return $db;
}
?>