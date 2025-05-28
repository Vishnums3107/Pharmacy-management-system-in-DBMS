<?php
session_start();
include_once('connect_db.php');
if(isset($_SESSION['username'])){
    $id=$_SESSION['admin_id'];
    $username=$_SESSION['username'];
}else{
    header("location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php");
    exit();
}

$message = $message1 = '';
if(isset($_POST['submit'])){
    $fname=$_POST['first_name'];
    $lname=$_POST['last_name'];
    $sid=$_POST['staff_id'];
    $postal=$_POST['postal_address'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $user=$_POST['username'];
    $pas=$_POST['password'];
    
    $sql1=mysqli_query($conn,"SELECT * FROM pharmacist WHERE username='$user'")or die(mysql_error());
    $result=mysqli_fetch_array($sql1);
    if($result>0){
        $message="<div class='alert alert-warning'>Sorry, the username entered already exists</div>";
    }else{
        $sql=mysqli_query($conn,"INSERT INTO pharmacist(first_name,last_name,staff_id,postal_address,phone,email,username,password,date)
        VALUES('$fname','$lname','$sid','$postal','$phone','$email','$user','$pas',NOW())");
        if($sql>0) {
            echo '<script type="text/javascript">'; 
            echo 'alert("Pharmacist successfully added.");'; 
            echo 'window.location.href = "admin_pharmacist.php";';
            echo '</script>';
        }else{
            $message1="<div class='alert alert-error'>Registration Failed, Try again</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $username;?> - Pharmacy Management System</title>
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
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }
        
        #header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: 100%;
            z-index: 100;
        }
        
        #header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        #header .img {
            height: 40px;
        }
        
        #left_column {
            width: 250px;
            background: white;
            height: 100vh;
            position: fixed;
            top: 80px;
            left: 0;
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
            margin-left: 250px;
            margin-top: 80px;
            padding: 2rem;
            width: calc(100% - 250px);
        }
        
        .tabbed_box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 25px;
            margin-top: 20px;
        }
        
        .tabbed_box h4 {
            font-size: 1.3rem;
            color: var(--dark);
            margin-bottom: 15px;
        }
        
        .tabs {
            display: flex;
            list-style: none;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        
        .tabs li a {
            padding: 10px 20px;
            color: var(--gray);
            text-decoration: none;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tabs li a:hover,
        .tabs li a.active {
            color: var(--primary);
            border-bottom: 3px solid var(--primary);
        }
        
        .content {
            display: none;
        }
        
        .content.active {
            display: block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        table th {
            background-color: var(--primary-light);
            color: white;
            font-weight: 500;
        }
        
        table tr:hover {
            background-color: #f8f9fa;
        }
        
        table img {
            width: 25px;
            height: 25px;
            transition: all 0.3s;
        }
        
        table img:hover {
            transform: scale(1.1);
        }
        
        fieldset {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        legend {
            padding: 0 10px;
            color: var(--primary);
            font-weight: 500;
        }
        
        .insert {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .insert p {
            margin-bottom: 0;
        }
        
        .insert input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .insert input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(107, 115, 255, 0.2);
        }
        
        .insert input[type="submit"] {
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 500;
            grid-column: span 2;
            padding: 12px;
            margin-top: 10px;
        }
        
        .insert input[type="submit"]:hover {
            background: var(--secondary);
        }
        
        .alert {
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            #left_column {
                width: 100%;
                height: auto;
                position: static;
                margin-top: 80px;
            }
            
            #main {
                margin-left: 0;
                width: 100%;
            }
            
            .insert {
                grid-template-columns: 1fr;
            }
            
            .insert input[type="submit"] {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div id="header">
        <h1><img src="images/pharmacistNew.png" class="img"> Pharmacy Management System</h1>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span><?php echo $username; ?></span>
        </div>
    </div>
    
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
        <div id="tabbed_box" class="tabbed_box">  
            <h4>Manage Pharmacists</h4> 
            <hr/>	
            <div class="tabbed_area">  
                <ul class="tabs">  
                    <li><a href="#content_1" class="active">VIEW PHARMACISTS</a></li>  
                    <li><a href="#content_2">ADD PHARMACIST</a></li>  
                </ul>  
                
                <div id="content_1" class="content active">  
                    <?php echo $message; echo $message1; ?>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM pharmacist")or die(mysql_error());
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Firstname</th><th>Lastname</th><th>Username</th><th>Update</th><th>Delete</th></tr>";
                    
                    while($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo '<td>' . $row['pharmacist_id'] . '</td>';
                        echo '<td>' . $row['first_name'] . '</td>';
                        echo '<td>' . $row['last_name'] . '</td>';
                        echo '<td>' . $row['username'] . '</td>';
                        echo '<td><a href="update_pharmacist.php?id='.$row['pharmacist_id'].'"><img src="images/updateNew.png" alt="Update"/></a></td>';
                        echo '<td><a href="delete_pharmacist.php?id='.$row['pharmacist_id'].'"><img src="images/deleteNew.png" alt="Delete"/></a></td>';
                        echo "</tr>";
                    } 
                    echo "</table>";
                    ?> 
                </div>  
                
                <div id="content_2" class="content">  
                    <fieldset>
                        <legend>Add Pharmacist</legend>
                        <form name="form1" onsubmit="return validateForm();" action="admin_pharmacist.php" method="post" class="insert">
                            <p><input name="first_name" type="text" placeholder="First Name" required id="first_name" /></p>
                            <p><input name="last_name" type="text" placeholder="Last Name" required id="last_name" /></p>
                            <p><input name="staff_id" type="text" placeholder="Staff ID" required id="staff_id"/></p> 
                            <p><input name="postal_address" type="text" placeholder="Address" required id="postal_address"/></p> 
                            <p><input name="phone" type="text" placeholder="Phone" required id="phone"/></p>
                            <p><input name="email" type="text" placeholder="Email" required id="email"/></p>   
                            <p><input name="username" type="text" placeholder="Username" required id="username"/></p>
                            <p><input name="password" type="text" placeholder="Password" required id="password"/></p>
                            <p><input name="submit" type="submit" value="Add Pharmacist"></p>
                        </form>
                    </fieldset>
                </div>  
            </div>  
        </div>
    </div>
    
    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tabs li a');
            const contents = document.querySelectorAll('.content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked tab and corresponding content
                    this.classList.add('active');
                    const contentId = this.getAttribute('href');
                    document.querySelector(contentId).classList.add('active');
                });
            });
            
            // Form validation
            function validateForm() {
                const fname = document.form1.first_name.value;
                const valid = /^[a-zA-Z ]+$/;
                
                if(!valid.test(fname)) {
                    alert("First Name Cannot Contain Numerical Values");
                    document.form1.first_name.value = "";
                    document.form1.first_name.focus();
                    return false;
                }
                
                if(fname === "") {
                    alert("Name Field is Empty");
                    return false;
                }
                
                const lname = document.form1.last_name.value;
                if(!valid.test(lname)) {
                    alert("Last Name Cannot Contain Numerical Values");
                    document.form1.last_name.value = "";
                    document.form1.last_name.focus();
                    return false;
                }
                
                if(lname === "") {
                    alert("Last Name Field is Empty");
                    return false;
                }
                
                return true;
            }
            
            window.validateForm = validateForm;
        });
    </script>
</body>
</html>