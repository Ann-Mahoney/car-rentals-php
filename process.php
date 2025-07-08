<?php
session_start();
require_once("database.php");
// process.php - Handle form submission with validation and insert data using PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $name = trim($_POST['name'] ?? '');
    $offer = trim($_POST['offer'] ?? '');
    $pickup = trim($_POST['pickup'] ?? '');
    $return_date = trim($_POST['return_date'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    $errors = [];

    // Validate required fields
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name can only contain letters and spaces.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($contact)) {
        $errors[] = "Contact is required.";
    } elseif (!preg_match("/^\d{1,10}$/", $contact)) {
        $errors[] = "Contact must contain only digits and be at most 10 characters long.";
    }
    if (empty($pickup)) {
        $errors[] = "Pick-up date is required.";
    }
    if (empty($return_date)) {
        $errors[] = "Return date is required.";
    }
    if (empty($comment)) {
        $errors[] = "Comment is required.";
    }

    // Validate date formats and logic
    if (!empty($pickup) && !DateTime::createFromFormat('Y-m-d', $pickup)) {
        $errors[] = "Invalid pick-up date format.";
    }
    if (!empty($return_date) && !DateTime::createFromFormat('Y-m-d', $return_date)) {
        $errors[] = "Invalid return date format.";
    }
    if (!empty($pickup) && !empty($return_date)) {
        $pickupDate = new DateTime($pickup);
        $returnDate = new DateTime($return_date);
        if ($returnDate < $pickupDate) {
            $errors[] = "Return date cannot be earlier than pick-up date.";
        }
    }

    if (!empty($errors)) {
        // Display errors
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        echo "<p><a href='index.php'>Go back to the form</a></p>";
        exit;
    }

    try {
        // Instantiate Database and get PDO connection
        $db = new Database("localhost", "root", "", "car_rentals");
        $pdo = $db->connect();

        // Prepare the SQL insert statement
        $stmt = $pdo->prepare("INSERT INTO booking (name, offer, pickup, return_date, email, contact, comment) VALUES (:name, :offer, :pickup, :return_date, :email, :contact, :comment)");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':offer', $offer);
        $stmt->bindParam(':pickup', $pickup);
        $stmt->bindParam(':return_date', $return_date);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':comment', $comment);

        // Execute the statement
        $stmt->execute();

        // Redirect or display success message
        echo "<p style='color:green;'>Booking successful!</p>";
        echo "<p>Hello, " . htmlspecialchars($name) . "!</p>";
        echo "<p>Your booking details:</p>";
        echo "<ul>";
        echo "<li>Offer: " . htmlspecialchars($offer) . "</li>";
        echo "<li>Pick-up date: " . htmlspecialchars($pickup) . "</li>";
        echo "<li>Return date: " . htmlspecialchars($return_date) . "</li>";
        echo "<li>Email: " . htmlspecialchars($email) . "</li>";
        echo "<li>Contact: " . htmlspecialchars($contact) . "</li>";
        echo "<li>Comment: " . nl2br(htmlspecialchars($comment)) . "</li>";
        echo "</ul>";
        echo "<p><a href='index.php'>Make another booking</a></p>";
    } catch (PDOException $e) {
        // Handle error
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        echo "<p><a href='index.php'>Go back to the form</a></p>";
    }
} else {
    // If not a POST request, redirect to form page
    header('Location: index.php');
    exit;
}
?>
