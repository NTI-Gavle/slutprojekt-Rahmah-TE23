<?php
/**
 * Database connection using PDO
 */

function getDB() {
    static $db = null;
    
    if ($db === null) {
        try {
            $host = 'localhost';
            $dbname = 'kvitter';
            $username = 'root';
            $password = 'Root';
      
            
            //  ÄNDRA HÄR - Testa dessa alternativ:
                 // Alternativ 1: Tomt (vanligast för XAMPP)
            // $password = 'root';   // Alternativ 2: "root"
            // $password = 'mysql';   // Alternativ 3: "mysql"
            // $password = 'password'; // Alternativ 4: "password"
            
            $db = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password
            );
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            die("Databasanslutning misslyckades: " . $e->getMessage());
        }
    }
    
    return $db;
}
?>