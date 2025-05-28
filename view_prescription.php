<?php
session_start();
include_once('connect_db.php');
if(isset($_SESSION['username'])) {
    $id = $_SESSION['manager_id'];
    $fname = $_SESSION['first_name'];
    $lname = $_SESSION['last_name'];
    $sid = $_SESSION['staff_id'];
    $user = $_SESSION['username'];
} else {
    header("location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user); ?> - Pharmacy Management System</title>
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
        
        .action-btn {
            color: var(--primary);
            text-decoration: none;
            margin: 0 5px;
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
            $qury = mysqli_query($conn, "SELECT * FROM stock WHERE status='low'") or die(mysqli_error());
            $ros = mysqli_num_rows($qury);
            if($ros > 0) {
                echo '<p class="dd"><img src="images/red.png" class="imgc"> Low stock</p>';
            } else {
                echo '<p class="ddc"><img src="images/green.png" class="imgc"> Enough stock</p>';
            }
            ?>
        </div>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span><?php echo htmlspecialchars($fname." ".$lname); ?></span>
        </div>
    </div>
    
    <div id="content">
        <div id="left_column">
            <div id="button">
                <ul>
                    <li><a href="manager.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="view.php"><i class="fas fa-users"></i> View Users</a></li>
                    <li><a href="view_prescription.php" class="active"><i class="fas fa-prescription-bottle-alt"></i> View Prescriptions</a></li>
                    <li><a href="stock.php"><i class="fas fa-pills"></i> Manage Stock</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>    
            </div>
        </div>
        
        <div id="main">
            <div class="card">
                <h4>View Prescriptions</h4>
                <hr/>
                
                <div class="tabbed_area">  
                    <ul class="tabs">  
                        <li><a href="javascript:tabSwitch('tab_1', 'content_1');" id="tab_1" class="active">Prescriptions</a></li>  
                    </ul>  
                    
                    <div id="content_1" class="content">  
                        <?php echo isset($message1) ? '<div class="alert">'.$message1.'</div>' : ''; ?>
                        
                        <?php
                        $result = mysqli_query($conn, "SELECT DISTINCT * FROM prescription") or die(mysqli_error());
                        if(mysqli_num_rows($result) > 0) {
                            echo "<table>";
                            echo "<thead><tr>
                                    <th>Customer</th>
                                    <th>Prescription No</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr></thead>";
                            echo "<tbody>";
                            
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo '<td>' . htmlspecialchars($row['customer_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['prescription_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['invoice_id']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                                echo '<td>
                                        <a href="view_prescription_details.php?id='.htmlspecialchars($row['prescription_id']).'" class="action-btn" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                      </td>';
                                echo "</tr>";
                            } 
                            
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo '<div class="alert">No prescriptions found</div>';
                        }
                        ?> 
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
        
        // Initialize first tab as active
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('content_1').style.display = 'block';
        });
    </script>
</body>
</html>