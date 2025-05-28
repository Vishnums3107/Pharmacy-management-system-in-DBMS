<?php
session_start();
include_once('connect_db.php');
if(isset($_SESSION['username'])){
    $id=$_SESSION['pharmacist_id'];
    $user=$_SESSION['username'];
    $fname=$_SESSION['first_name'];
    $lname=$_SESSION['last_name'];
}else{
    header("location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."index.php");
    exit();
}

if(isset($_POST['submit'])){
    $sname=$_POST['drug_name'];
    $cat=$_POST['category'];
    $des=$_POST['description'];
    $com=$_POST['company'];
    $sup=$_POST['supplier'];
    $qua=$_POST['quantity'];
    $cost=$_POST['cost'];
    $sta="Available";

    $sql=mysqli_query($conn, "INSERT INTO stock(drug_name,category,description,company,supplier,quantity,cost,status,date_supplied)
    VALUES('$sname','$cat','$des','$com','$sup','$qua','$cost','$sta',NOW())");
    
    if($sql>0) {
        header("location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/stock_pharmacist.php");
    }else{
        $message1="<div class='alert error'>Registration Failed, Try again</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user;?> - Pharmacy Management System</title>
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
        
        .img {
            height: 40px;
        }
        
        .stock-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        
        .imgc {
            width: 20px;
            height: 20px;
        }
        
        .dd {
            color: var(--danger);
        }
        
        .ddc {
            color: var(--success);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: var(--primary-light);
            color: white;
            font-weight: 500;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .status-available {
            color: var(--success);
            font-weight: 500;
        }
        
        .status-low {
            color: var(--danger);
            font-weight: 500;
        }
        
        .action-btn {
            color: var(--danger);
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            opacity: 0.8;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .error {
            background-color: #ffebee;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .success {
            background-color: #e8f5e9;
            color: var(--success);
            border-left: 4px solid var(--success);
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
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div id="header">
        <h1><img src="images/patientNew.png" class="img"> Pharmacy Management System</h1>
        <div class="stock-indicator">
            <?php 
            include('connect_db.php');
            $qury=mysqli_query($conn, "SELECT * from stock where status='low'") or die(mysqli_error());
            $ros=mysqli_num_rows($qury);
            if($ros>0){
                echo '<p class="dd"><img src="images/red.png" class="imgc"> Low stock items</p>';
            }else{
                echo '<p class="ddc"><img src="images/green.png" class="imgc"> Stock levels good</p>';
            }
            ?>
        </div>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span><?php echo $fname." ".$lname; ?></span>
        </div>
    </div>
    
    <div id="left_column">
        <div id="button">
            <ul>
                <li><a href="pharmacist.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="prescription.php"><i class="fas fa-prescription-bottle-alt"></i> Prescription</a></li>
                <li><a href="stock_pharmacist.php" class="active"><i class="fas fa-pills"></i> Stock</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>    
        </div>
    </div>
    
    <div id="main">
        <div class="card">
            <h4>Manage Stock</h4>
            <hr/>
            
            <div class="tabbed_area">  
                <ul class="tabs">  
                    <li><a href="javascript:tabSwitch('tab_1', 'content_1');" id="tab_1" class="active">View Stock</a></li>  
                </ul>  
                
                <div id="content_1" class="content">  
                    <?php echo $message; ?>
                    <?php echo $message1; ?>
                    
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM stock") 
                            or die(mysqli_error());
                    
                    echo "<table>";
                    echo "<thead><tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Available Stock</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr></thead>";
                    echo "<tbody>";

                    while($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo '<td>' . $row['stock_id'] . '</td>';               
                        echo '<td>' . $row['drug_name'] . '</td>';
                        echo '<td>' . $row['quantity'] . " " . $row['category'] . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '<td class="status-' . strtolower($row['status']) . '">' . $row['status'] . '</td>';
                        echo '<td><a href="deletestock1.php?id=' . $row['stock_id'] . '" class="action-btn"><i class="fas fa-trash-alt"></i></a></td>';
                        echo "</tr>";
                    } 
                    
                    echo "</tbody>";
                    echo "</table>";
                    ?> 
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
        
        // Initialize first tab as active
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('content_1').style.display = 'block';
        });
    </script>
</body>
</html>