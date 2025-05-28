<?php
session_start();
include_once('connect_db.php');
if(isset($_GET['id'])){
    $userid = $_GET['id'];
} else {
    header("location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php");
    exit();
}

// Fetch cashier data
$queryfetch = mysqli_query($conn, "SELECT * FROM cashier WHERE cashier_id='".$userid."'") or die(mysqli_error());
$rows = mysqli_num_rows($queryfetch);
if($rows > 0){
    while($data = mysqli_fetch_array($queryfetch)){
        $first_namec = $data['first_name'];
        $last_namec = $data['last_name'];
        $staff_idc = $data['staff_id'];
        $postalc = $data['postal_address'];
        $phonec = $data['phone'];
        $emailc = $data['email'];
        $usernamec = $data['username'];
        $passwordc = $data['password'];
    }
} else {
    echo '<script>window.alert("No record found")</script>';
}

// Handle form submission
if(isset($_POST['submitu'])){
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $sid = $_POST['staff_id'];
    $postal = $_POST['postal_address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $pas = $_POST['password'];
    
    $sql = mysqli_query($conn, "UPDATE cashier SET first_name='$fname', last_name='$lname', staff_id='$sid', postal_address='$postal', phone='$phone', email='$email', username='$username', password='$pas' WHERE cashier_id='".$userid."'");
    
    if($sql) {
        echo '<script type="text/javascript"> 
            alert("Record updated successfully."); 
            window.location.href = "admin.php";
        </script>';
    } else {
        $message1 = "<div class='alert error'>Update Failed, Try again</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Cashier - Pharmacy Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --secondary: #3f37c9;
            --dark: #1b263b;
            --light: #f8f9fa;
            --gray: #adb5bd;
            --success: #4cc9f0;
            --warning: #f72585;
            --danger: #ef233c;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--dark);
        }
        
        #header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        
        #header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .img {
            height: 40px;
        }
        
        #content {
            display: flex;
            min-height: calc(100vh - 80px);
        }
        
        #left_column {
            width: 250px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding: 2rem 0;
        }
        
        #button ul {
            list-style: none;
        }
        
        #button ul li {
            margin-bottom: 5px;
        }
        
        #button ul li a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        
        #button ul li a:hover,
        #button ul li a.active {
            background: var(--primary-light);
            color: white;
        }
        
        #button ul li a i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        #main {
            flex: 1;
            padding: 2rem;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        h4 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: var(--secondary);
        }
        
        hr {
            border: none;
            height: 1px;
            background: #eee;
            margin: 1rem 0;
        }
        
        .tabs {
            display: flex;
            list-style: none;
            border-bottom: 2px solid #eee;
            margin-bottom: 1.5rem;
        }
        
        .tabs li a {
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
        }
        
        .tabs li a.active,
        .tabs li a:hover {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
        }
        
        fieldset {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        legend {
            padding: 0 10px;
            color: var(--secondary);
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 1.2rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .form-group label {
            width: 150px;
            font-weight: 500;
            margin-right: 15px;
        }
        
        .form-control {
            flex: 1;
            min-width: 200px;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background-color: var(--secondary);
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        
        .error {
            background-color: #ffebee;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        @media (max-width: 768px) {
            #content {
                flex-direction: column;
            }
            
            #left_column {
                width: 100%;
                height: auto;
                padding: 1rem 0;
            }
            
            #button ul {
                display: flex;
                overflow-x: auto;
                padding: 0 1rem;
            }
            
            #button ul li {
                flex: 0 0 auto;
            }
            
            .form-group label {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .form-control {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div id="header">
        <h1><img src="images/hd_logo.jpg" class="img"> Pharmacy Management System</h1>
    </div>
    
    <div id="content">
        <div id="left_column">
            <div id="button">
                <ul>
                    <li><a href="admin.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="admin_pharmacist.php"><i class="fas fa-user-md"></i> Pharmacist</a></li>
                    <li><a href="admin_manager.php"><i class="fas fa-user-tie"></i> Manager</a></li>
                    <li><a href="admin_cashier.php"><i class="fas fa-cash-register"></i> Cashier</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>    
            </div>
        </div>
        
        <div id="main">
            <div class="card">
                <h4>Manage Cashier</h4>
                <hr/>
                
                <div class="tabbed_area">  
                    <ul class="tabs">  
                        <li><a href="javascript:tabSwitch('tab_1', 'content_1');" id="tab_1" class="active">Update Cashier</a></li>  
                    </ul>  
                    
                    <div id="content_1" class="content">  
                        <?php echo isset($message1) ? $message1 : ''; ?>
                        
                        <fieldset> 
                            <legend>Update cashier</legend>
                            <form name="myform" onsubmit="return validateForm(this);" action="" method="post">
                                <div class="form-group">
                                    <label for="first_name">First Name:</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="<?php echo $first_namec; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="last_name">Last Name:</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo $last_namec; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="staff_id">Staff ID:</label>
                                    <input type="text" class="form-control" name="staff_id" id="staff_id" placeholder="Staff ID" value="<?php echo $staff_idc; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="postal_address">Postal Address:</label>
                                    <input type="text" class="form-control" name="postal_address" id="postal_address" placeholder="Address" value="<?php echo $postalc; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="<?php echo $phonec; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $emailc; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo $usernamec; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="text" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo $passwordc; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label></label>
                                    <button type="submit" name="submitu" class="btn">Update Cashier</button>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function tabSwitch(tabId, contentId) {
            // Remove active class from all tabs and contents
            document.querySelectorAll('.tabs li a').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Add active class to clicked tab and show corresponding content
            document.getElementById(tabId).classList.add('active');
            document.getElementById(contentId).style.display = 'block';
        }
        
        // Form validation
        function validateForm(form) {
            // Add your validation logic here
            return true; // Return false to prevent submission if validation fails
        }
        
        // Initialize first tab as active
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('content_1').style.display = 'block';
        });
    </script>
</body>
</html>