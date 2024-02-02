<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pname = $_POST['schedule_date'];
    $stock = $_POST['schedule_status'];

    try {
        // Use the function to get a PDO connection
        $conn = connectDB();

        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO schedule_tbl (s_date, s_status) VALUES (:prod, 'Available')";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':prod', $pname);
        $stmt->execute();

        // Redirect back to the user data page after successful insertion
        header("Location: ../schedule.php");
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
