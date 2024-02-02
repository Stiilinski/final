<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u_name = $_POST['customer'];
    $p_id = $_POST['selectProduct']; 

    try {
        // Use the function to get a PDO connection
        $conn = connectDB();

        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch user_id based on user_name
        $userQuery = $conn->prepare("SELECT p_id FROM patient_tbl WHERE p_name = :user_name");
        $userQuery->bindParam(':user_name', $u_name);
        $userQuery->execute();
        $u_id = $userQuery->fetchColumn();

        // Insert into orders table
        $sql = "INSERT INTO appointment_tbl (s_id, p_id) VALUES (:s_id, :p_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':p_id', $u_id);
        $stmt->bindParam(':s_id', $p_id);
        $stmt->execute();

        $sql = "UPDATE schedule_tbl SET s_status = 'Not Available' WHERE s_id = :s_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':s_id', $p_id);
        $stmt->execute();

        // Redirect back to the orders page after successful insertion
        header("Location: ../index.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        // Always close the connection
        if ($conn) {
            $conn = null;
        }
    }
}
?>
