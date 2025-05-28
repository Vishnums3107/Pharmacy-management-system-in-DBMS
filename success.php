<?php
session_start();
include_once('connect_db.php');
if(isset($_SESSION['username'])){
    $id=$_SESSION['admin_id'];
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
            --sienna: #a0522d;
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
            justify-content: space-between;
        }
        
        #header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        #header img {
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
        
        .success-message {
            color: var(--sienna);
            margin: 1rem 0 2rem;
            font-size: 1.25rem;
            text-align: center;
            padding: 1rem;
            background: rgba(160, 82, 45, 0.1);
            border-radius: 8px;
            border-left: 4px solid var(--sienna);
        }
        
        .grid_7 {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
            width: 75px;
            height: 75px;
            margin-bottom: 15px;
            object-fit: contain;
            border-radius: 50%;
        }
        
        .dashboard-module span {
            font-weight: 500;
            font-size: 0.95rem;
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
            
            .grid_7 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div id="header">
        <h1><img src="images/hd_logo.jpg" alt="Pharmacy Logo"> Pharmacy Management System</h1>
    </div>
    
    <div id="content">
        <div id="left_column">
            <div id="button">
                <ul>
                    <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="admin_pharmacist.php"><i class="fas fa-user-md"></i> Pharmacist</a></li>
                    <li><a href="admin_manager.php"><i class="fas fa-user-tie"></i> Manager</a></li>
                    <li><a href="admin_cashier.php"><i class="fas fa-cash-register"></i> Cashier</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>    
            </div>
        </div>
        
        <div id="main">
            <div class="success-message">
                You have successfully added an entity into the database
            </div>
            
            <!-- Dashboard icons -->
            <div class="grid_7">
                <a href="admin.php" class="dashboard-module">
                    <img src="images/admin_icon.jpg" alt="Dashboard Icon">
                    <span>Dashboard</span>
                </a>
                <a href="admin_pharmacist.php" class="dashboard-module">
                    <img src="images/pharmacist_icon.jpg" alt="Pharmacist Icon">
                    <span>Manage Pharmacist</span>
                </a>
                <a href="admin_manager.php" class="dashboard-module">
                    <img src="images/manager_icon.png" alt="Manager Icon">
                    <span>Manage Manager</span>
                </a>
                <a href="admin_cashier.php" class="dashboard-module">
                    <img src="images/cashier_icon.jpg" alt="Cashier Icon">
                    <span>Manage Cashier</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>