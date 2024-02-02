<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #container {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 80%;
            max-width: 600px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, #selectOption, #selectProduct {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div id="container">
    <h2 style="text-align: center;">Appointment</h2>

    <?php
    include 'includes/db_connection.php';

    try {
        $conn = connectDB();

        if ($conn && isset($_POST['order_id'])) {
            $userId = $_POST['order_id'];
            $sql = "SELECT p_id, p_name, p_email FROM patient_tbl WHERE p_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($userData) {
                ?>
                <form method="post" action="includes/insert_app.php">
                    <label for="customer">Patient's Name:</label>
                    <input type="text" id="customer" name="customer" value="<?php echo $userData['p_name']; ?>" required readonly>

                    <label for="selectProduct">Select Available Date:</label>
                    <select name="selectProduct" id="selectProduct">
                        <option value="">Select Available Date</option>
                        <?php
                        try 
                        {
                            $stmt = $conn->query("SELECT * FROM schedule_tbl WHERE s_status = 'Available'");
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($result as $row) 
                            {
                                $productName = $row['s_date'];
                                echo "<option value='{$row['s_id']}'>$productName</option>";
                            }
                        } 
                        catch (PDOException $e) 
                        {
                            die("Query failed: " . $e->getMessage());
                        }
                        ?>
                    </select>

                    <button type="submit">Insert</button>
                    <a href="index.php"><button type="button">Users</button></a>
                </form>
                <?php
            } else {
                echo "<p>User not found.</p>";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        if ($conn) {
            $conn = null;
        }
    }
    ?>
</div>

</body>
</html>
