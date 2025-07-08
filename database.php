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
        $this->connect();
    }

    public function connect(){
        try{
        $dsn = "mysql:host={$this->hostname};dbname={$this->database}";
        $this->db= new PDO($dsn, $this->username, $this->password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->db;
        }catch(PDOException $e){
            echo "server down". $e->getMessage();
        }
    }

    public function storeCarRental($name, $email, $contact, $pickup, $return_date, $offer, $comment){
       try{
         $query = $this->db->prepare("INSERT INTO `booking`( `name`, `email`, `offer`, `contact`, `pickup`, `return_date`, `comment`)
         VALUES (?,?,?,?,?,?,?)");

         $query->bindParam(1, $name);
         $query->bindParam(2, $email);
         $query->bindParam(3, $contact);
         $query->bindParam(4, $pickup);
         $query->bindParam(5, $return_date);
         $query->bindParam(6, $offer);
         $query->bindParam(7, $comment);

         $query->execute();
         return true;
       }catch(PDOException $e){
        echo "failed to insert into the table". $e->getMessage();
       }
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
