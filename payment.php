<?php
session_start();
include_once('connect_db.php');

if(isset($_SESSION['username'])){
    $id=$_SESSION['cashier_id'];
    $fname=$_SESSION['first_name'];
    $lname=$_SESSION['last_name'];
    $sid=$_SESSION['staff_id'];
    $user=$_SESSION['username'];
} else {
    header("location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php");
    exit();
}

$SN = mysqli_query($conn, "SELECT 1+MAX(serialno) FROM receipts");
$invoice = mysqli_fetch_array($SN);
$invoiceNo = ($invoice[0] == '') ? 1000 : $invoice[0];
$serno = $invoiceNo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user;?> - Pharmacy Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        
        .payment-form {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 400px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(107, 115, 255, 0.2);
        }
        
        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: var(--secondary);
        }
        
        #viewer1 {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        
        #viewer2 {
            color: var(--dark);
        }
        
        .error {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: 5px;
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
        }
    </style>
</head>
<body>
    <div id="header">
        <h1><img src="images/patientNew.png" class="img"> Pharmacy Management System</h1>
        <div class="header-right">
            <?php 
            $qury = mysqli_query($conn, "SELECT * from stock where status='low'") or die(mysqli_error());
            $ros = mysqli_num_rows($qury);
            if($ros > 0){
                ?>
                <div class="stock-indicator stock-low">
                    <img src="images/red.png" class="imgc" alt="Low stock">
                    <span>Low stock items</span>
                </div>
            <?php
            } else {
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
        <div class="tabbed_box">  
            <h4>Manage Payments</h4> 
            <hr/>	
            <div class="payment-form">
                <form name="myform" onsubmit="return validateForm(this);" action="receipt.php" method="post">
                    <div class="form-group">
                        <label for="invoice_no">Invoice Number</label>
                        <input type="text" class="form-control" name="invoice_no" id="invoice_no" placeholder="Enter invoice number" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_type">Payment Method</label>
                        <select class="form-control" name="payType" id="payment_type" required>
                            <option value="">Select Payment Method</option>
                            <?php
                            $getpayType = mysqli_query($conn, "SELECT * FROM paymentTypes");
                            while($pType = mysqli_fetch_array($getpayType)) {
                                echo "<option value='".$pType['Name']."'>".$pType['Name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="serial_no">Serial Number</label>
                        <input type="text" class="form-control" name="serial_no" id="serial_no" value="<?php echo $serno ?>" readonly>
                    </div>
                    
                    <button type="submit" name="tuma" class="btn">Process Payment</button>
                    
                    <div id="viewer1">
                        <span id="viewer2"></span>
                    </div>
                </form>
            </div>
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
        
        // Form validation
        function validateForm(form) {
            if(!form.invoice_no.value) {
                alert('Please provide an invoice number');
                return false;
            }
            return true;
        }
        
        // Invoice number validation
        $(document).ready(function() {
            $("#invoice_no").change(function() {    
                var invoice_no = $(this).val();
                
                if(invoice_no.length > 0) {
                    $.ajax({
                        type: "POST", 
                        url: "check.php", 
                        data: 'invoice_no='+invoice_no, 
                        success: function(msg) {
                            if(msg) {
                                $("#viewer2").html(msg);
                            } else {
                                $("#viewer2").html('<span class="error">Invoice does not exist</span>');
                            }
                        }    
                    }); 
                }
            });        
        });
    </script>
</body>
</html>