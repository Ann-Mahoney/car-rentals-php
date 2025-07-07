<?php
// database.php - PDO database connection

// $host = 'localhost'; // Change if needed
// $dbname = 'car_rentals'; // Change to your database name
// $username = 'root'; // Change to your database username
// $password = ''; // Change to your database password

class Database{
    private $db;
    private $hostname;
    private $username;
    private $password;
    private $database;

    public function __construct($hostname, $username, $password, $database){
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect(){
        $dsn = "mysql:host={$this->hostname};dbname={$this->database}";
        $this->db= new PDO($dsn, $this->username, $this->password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->db;
    }
}

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
//     // Set error mode to exceptions
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Database connection failed: " . $e->getMessage());
// }
?>
