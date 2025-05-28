<?php
session_start();
include_once('connect_db.php');
if(isset($_SESSION['username'])){
    $id=$_SESSION['cashier_id'];
    $fname=$_SESSION['first_name'];
    $lname=$_SESSION['last_name'];
    $sid=$_SESSION['staff_id'];
    $user=$_SESSION['username'];
}else{
    header("location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php");
    exit();
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
            --danger: #f94144;
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
        
        .stock-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        
        .stock-low {
            background-color: #fee2e2;
            color: var(--danger);
        }
        
        .stock-ok {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .imgc {
            width: 16px;
            height: 16px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
        }
        
        .user-info i {
            color: var(--primary);
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
        
        .grid_7 {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
        .dashboard-module {
            background: white;
            border-radius: 10px;
            padding: 25px 20px;
            text-align: center;
            text-decoration: none;
            color: var(--dark);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .dashboard-module:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            color: var(--primary);
        }
        
        .dashboard-module img {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
            object-fit: contain;
        }
        
        .dashboard-module span {
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
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
            
            .header-right {
                flex-direction: column;
                align-items: flex-end;
                gap: 5px;
            }
            
            .grid_7 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div id="header">
        <h1><img src="images/PatientNew.png" class="img"> Pharmacy Management System</h1>
        <div class="header-right">
            <?php 
            $qury=mysqli_query($conn, "SELECT * from stock where status='low'") or die(mysqli_error());
            $ros=mysqli_num_rows($qury);
            if($ros>0){
                ?>
                <div class="stock-indicator stock-low">
                    <img src="images/red.png" class="imgc" alt="Low stock">
                    <span>Low stock items</span>
                </div>
            <?php
            }else{
                ?>
                <div class="stock-indicator stock-ok">
                    <img src="images/green.png" class="imgc" alt="Enough stock">
                    <span>Stock levels OK</span>
                </div>
            <?php
            }
            ?>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $fname." ".$lname; ?></span>
            </div>
        </div>
    </div>
    
    <div id="left_column">
        <div id="button">
            <ul>
                <li><a href="cashier.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="payment.php" target="_top"><i class="fas fa-cash-register"></i> Process Payment</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>	
        </div>
    </div>
    
    <div id="main">
        <!-- Dashboard icons -->
        <div class="grid_7">
            <a href="cashier.php" class="dashboard-module">
                <img src="images/dashboardNew.png" alt="Dashboard">
                <span>Dashboard</span>
            </a>
            <a href="payment.php" target="_top" class="dashboard-module">
                <img src="images/debitNew.png" alt="Process Payment">
                <span>Process Payment</span>
            </a>
        </div>
    </div>
    
    <script>
        // Highlight current page in navigation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const links = document.querySelectorAll('#button ul li a');
            
            links.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>