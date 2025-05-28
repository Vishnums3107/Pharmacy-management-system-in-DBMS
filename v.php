<?php
include_once 'connect_db.php';

// Initialize message variable
$message = '';

// Process login if form submitted
if(isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Don't escape password - we'll hash it
    $position = $_POST['position'];
    
    if(!empty($position)) {
        switch($position) {
            case 'Admin':
                $result = mysqli_query($conn, "SELECT admin_id, username, password FROM admin WHERE username='$username'");
                break;
            case 'Pharmacist':
                $result = mysqli_query($conn, "SELECT pharmacist_id as id, username, password, first_name, last_name, staff_id FROM pharmacist WHERE username='$username'");
                break;
            case 'Cashier':
                $result = mysqli_query($conn, "SELECT cashier_id as id, username, password, first_name, last_name, staff_id FROM cashier WHERE username='$username'");
                break;
            case 'Manager':
                $result = mysqli_query($conn, "SELECT manager_id as id, username, password, first_name, last_name, staff_id FROM manager WHERE username='$username'");
                break;
            default:
                $message = '<div class="alert warning" id="alert-message">Invalid position selected</div>';
                break;
        }

        if(isset($result) && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // First check if password matches directly (for backward compatibility)
            // Then check hashed password if direct match fails
            if($password === $row['password'] || password_verify($password, $row['password'])) {
                session_start();
                
                // Common session variables
                $_SESSION['username'] = $row['username'];
                $_SESSION['position'] = $position;
                
                // Position-specific session variables
                switch($position) {
                    case 'Admin':
                        $_SESSION['admin_id'] = $row['admin_id'];
                        header("Location: admin.php");
                        break;
                    case 'Pharmacist':
                        $_SESSION['pharmacist_id'] = $row['id'];
                        $_SESSION['first_name'] = $row['first_name'];
                        $_SESSION['last_name'] = $row['last_name'];
                        $_SESSION['staff_id'] = $row['staff_id'];
                        header("Location: pharmacist.php");
                        break;
                    case 'Cashier':
                        $_SESSION['cashier_id'] = $row['id'];
                        $_SESSION['first_name'] = $row['first_name'];
                        $_SESSION['last_name'] = $row['last_name'];
                        $_SESSION['staff_id'] = $row['staff_id'];
                        header("Location: cashier.php");
                        break;
                    case 'Manager':
                        $_SESSION['manager_id'] = $row['id'];
                        $_SESSION['first_name'] = $row['first_name'];
                        $_SESSION['last_name'] = $row['last_name'];
                        $_SESSION['staff_id'] = $row['staff_id'];
                        header("Location: manager.php");
                        break;
                }
                exit();
            } else {
                $message = '<div class="alert error" id="alert-message">Invalid login credentials. Please try again.</div>';
            }
        } else {
            $message = '<div class="alert error" id="alert-message">Invalid login credentials. Please try again.</div>';
        }
    } else {
        $message = '<div class="alert warning" id="alert-message">Please select your login category</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pharmacy Management System - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        /* [Previous CSS styles remain exactly the same] */
    </style>
</head>
<body>
    <!-- [Previous HTML structure remains exactly the same] -->
</body>
</html>