<?php
include_once 'connect_db.php';

// Initialize message variable
$message = '';

// Process login if form submitted
if(isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $position = $_POST['position'];
    
    if(!empty($position)) {
        switch($position) {
            case 'Admin':
                $result = mysqli_query($conn, "SELECT admin_id, username FROM admin WHERE username='$username' AND password='$password'");
                $redirect = "admin.php";
                $session_vars = ['admin_id', 'username'];
                break;
            case 'Pharmacist':
                $result = mysqli_query($conn, "SELECT pharmacist_id, first_name, last_name, staff_id, username FROM pharmacist WHERE username='$username' AND password='$password'");
                $redirect = "pharmacist.php";
                $session_vars = ['pharmacist_id', 'first_name', 'last_name', 'staff_id', 'username'];
                break;
            case 'Cashier':
                $result = mysqli_query($conn, "SELECT cashier_id, first_name, last_name, staff_id, username FROM cashier WHERE username='$username' AND password='$password'");
                $redirect = "cashier.php";
                $session_vars = ['cashier_id', 'first_name', 'last_name', 'staff_id', 'username'];
                break;
            case 'Manager':
                $result = mysqli_query($conn, "SELECT manager_id, first_name, last_name, staff_id, username FROM manager WHERE username='$username' AND password='$password'");
                $redirect = "manager.php";
                $session_vars = ['manager_id', 'first_name', 'last_name', 'staff_id', 'username'];
                break;
        }

        if(isset($result) && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            session_start();
            
            // Set session variables dynamically
            foreach($session_vars as $index => $var_name) {
                $_SESSION[$var_name] = $row[$index];
            }
            
            header("Location: $redirect");
            exit();
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #f8f9fa;
            --text: #2b2d42;
            --light: #ffffff;
            --error: #ef233c;
            --warning: #ffb347;
            --gray: #adb5bd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            max-width: 1200px;
            width: 100%;
            text-align: center;
        }
        
        .header {
            margin-bottom: 30px;
            color: var(--light);
        }
        
        .header h1 {
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 2rem;
        }
        
        .header img {
            height: 50px;
            width: auto;
        }
        
        .login-box {
            background: var(--light);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            display: flex;
            max-width: 900px;
            margin: 0 auto;
            min-height: 500px;
        }
        
        .login-image {
            width: 50%;
            background: url('images/mylogin.png') center/cover no-repeat;
            display: none;
        }
        
        .login-form {
            padding: 50px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-title {
            font-size: 1.8rem;
            color: var(--text);
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .alert {
            display: block;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
        }
        
        .error {
            background-color: rgba(239, 35, 60, 0.1);
            color: var(--error);
            border-left: 4px solid var(--error);
        }
        
        .warning {
            background-color: rgba(255, 179, 71, 0.1);
            color: var(--warning);
            border-left: 4px solid var(--warning);
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text);
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--gray);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: var(--light);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        @media (min-width: 768px) {
            .login-image {
                display: block;
            }
            .login-form {
                width: 50%;
            }
        }
        
        @media (max-width: 576px) {
            .login-form {
                padding: 30px;
            }
            
            .form-title {
                font-size: 1.5rem;
                margin-bottom: 20px;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="header">
            <h1>
                <img src="images/pharmacyNew.png" alt="Pharmacy Logo">
                Pharmacy Management System
            </h1>
        </div>
        
        <div class="login-box">
            <div class="login-image" aria-hidden="true"></div>
            
            <div class="login-form">
                <h1 class="form-title">Login to Your Account</h1>
                <?php echo $message; ?>
                
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="position">Position</label>
                        <select id="position" name="position" class="form-control" required>
                            <option value="">Select your position</option>
                            <option value="Pharmacist">Pharmacist</option>
                            <option value="Admin">Admin</option>
                            <option value="Cashier">Cashier</option>
                            <option value="Manager">Manager</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="submit" class="btn">Login</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Hide alert message after 3 seconds
        setTimeout(function(){
            var alertMsg = document.getElementById('alert-message');
            if(alertMsg) {
                alertMsg.style.opacity = '0';
                setTimeout(function(){ 
                    alertMsg.style.display = 'none'; 
                }, 300);
            }
        }, 3000);
        
        // Focus on username field when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>