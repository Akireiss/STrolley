<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Backup Import</title>
    <style>
        /* Reset some default styles */
        body, h1, p {
            margin: 0;
            padding: 0;
        }

        /* Page container styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Header styles */
        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        /* Message styles */
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Button styles */
        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>MySQL Backup Import</h1>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $db = "strolley";
        $mysqli = new mysqli($servername, $username, $password, $db);

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        if ($_FILES["backup_file"]["error"] > 0) {
            echo '<div class="error-message">Error: ' . $_FILES["backup_file"]["error"] . '</div>';
        } else {
            $backup_file_name = $_FILES["backup_file"]["name"];
            $backup_file_tmp = $_FILES["backup_file"]["tmp_name"];

            // Check if the uploaded file is a valid SQL file
            if (pathinfo($backup_file_name, PATHINFO_EXTENSION) != "sql") {
                echo '<div class="error-message">Error: Invalid file format. Please upload a .sql file.</div>';
            } else {
                // Read the SQL file
                $sql_contents = file_get_contents($backup_file_tmp);

                // Split the SQL contents into individual queries
                $queries = explode(";", $sql_contents);

                foreach ($queries as $query) {
                    // Remove leading/trailing whitespace and empty lines
                    $query = trim($query);
                    if (!empty($query)) {
                        // Check if the query is a CREATE TABLE statement
                        if (strpos($query, "CREATE TABLE") === 0) {
                            // Extract the table name
                            preg_match("/CREATE TABLE `(.*)`/", $query, $matches);
                            $tableName = $matches[1];

                            // Check if the table already exists
                            if (!$mysqli->query("DESCRIBE `$tableName`")) {
                                // Table doesn't exist, create it
                                if ($mysqli->query($query)) {
                                    echo '<div class="success-message">Table `' . $tableName . '` created successfully.</div>';
                                } else {
                                    echo '<div class="error-message">Error creating table `' . $tableName . '`: ' . $mysqli->error . '</div>';
                                }
                            } else {
                                echo '<div class="success-message">Table `' . $tableName . '` already exists.</div>';
                            }
                        } else {
                            // Execute non-CREATE TABLE queries
                            if ($mysqli->query($query)) {
                                echo '<div class="success-message">Query executed successfully.</div>';
                            } else {
                                echo '<div class="error-message">Error executing query: ' . $mysqli->error . '</div>';
                            }
                        }
                    }
                }

                // Display a success message using JavaScript
                echo "<script>alert('Import completed successfully.');</script>";
            }
        }

        // Close the database connection
        $mysqli->close();
        ?>

        <!-- Add a button to return to mysqldump.php -->
        <div class="button-container">
            <button onclick="window.location.href='mysqldump.php'">Return to mysqldump.php</button>
        </div>
    </div>
</body>
</html>
