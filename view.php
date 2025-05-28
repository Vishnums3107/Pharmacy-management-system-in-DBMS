<?php
session_start();
include_once('connect_db.php');
if(isset($_SESSION['username'])){
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
    <title><?php echo $user; ?> - Pharmacy Management System</title>
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
            <span><?php echo $fname." ".$lname; ?></span>
        </div>
    </div>
    
    <div id="content">
        <div id="left_column">
            <div id="button">
                <ul>
                    <li><a href="manager.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="view.php" class="active"><i class="fas fa-users"></i> View Users</a></li>
                    <li><a href="view_prescription.php"><i class="fas fa-prescription-bottle-alt"></i> View Prescriptions</a></li>
                    <li><a href="stock.php"><i class="fas fa-pills"></i> Manage Stock</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>    
            </div>
        </div>
        
        <div id="main">
            <div class="card">
                <h4>View Users</h4>
                <hr/>
                
                <div class="tabbed_area">  
                    <ul class="tabs">  
                        <li><a href="javascript:tabSwitch('tab_1', 'content_1');" id="tab_1" class="active">Pharmacist</a></li>  
                        <li><a href="javascript:tabSwitch('tab_2', 'content_2');" id="tab_2">Cashier</a></li>
                        <li><a href="javascript:tabSwitch('tab_3', 'content_3');" id="tab_3">Manager</a></li>
                    </ul>  
                    
                    <div id="content_1" class="content">  
                        <?php echo isset($message) ? $message : ''; ?>
                        <?php echo isset($message1) ? $message1 : ''; ?>
                        
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM pharmacist") or die(mysqli_error());
                        echo "<table>";
                        echo "<thead><tr>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Staff ID</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr></thead>";
                        echo "<tbody>";
                        
                        while($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo '<td>' . $row['first_name'] . '</td>';
                            echo '<td>' . $row['last_name'] . '</td>';
                            echo '<td>' . $row['staff_id'] . '</td>';
                            echo '<td>' . $row['phone'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo "</tr>";
                        } 
                        
                        echo "</tbody>";
                        echo "</table>";
                        ?> 
                    </div>  
                    
                    <div id="content_2" class="content">  
                        <?php echo isset($message) ? $message : ''; ?>
                        <?php echo isset($message1) ? $message1 : ''; ?>
                        
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM cashier") or die(mysqli_error());
                        echo "<table>";
                        echo "<thead><tr>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Staff ID</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr></thead>";
                        echo "<tbody>";
                        
                        while($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo '<td>' . $row['first_name'] . '</td>';
                            echo '<td>' . $row['last_name'] . '</td>';
                            echo '<td>' . $row['staff_id'] . '</td>';
                            echo '<td>' . $row['phone'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo "</tr>";
                        } 
                        
                        echo "</tbody>";
                        echo "</table>";
                        ?>
                    </div>  
                    
                    <div id="content_3" class="content">  
                        <?php echo isset($message1) ? $message1 : ''; ?>
                        
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM manager") or die(mysqli_error());
                        echo "<table>";
                        echo "<thead><tr>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Staff ID</th>
                                <th>Phone</th>
                                <th>Email</th>
                            </tr></thead>";
                        echo "<tbody>";
                        
                        while($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo '<td>' . $row['first_name'] . '</td>';
                            echo '<td>' . $row['last_name'] . '</td>';
                            echo '<td>' . $row['Staff_id'] . '</td>';
                            echo '<td>' . $row['phone'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo "</tr>";
                        } 
                        
                        echo "</tbody>";
                        echo "</table>";
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