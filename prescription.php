<?php
session_start();
include_once('connect_db.php');
if(isset($_SESSION['username'])){
    $id=$_SESSION['pharmacist_id'];
    $fname=$_SESSION['first_name'];
    $lname=$_SESSION['last_name'];
    $sid=$_SESSION['staff_id'];
    $user=$_SESSION['username'];
}else{
    header("location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/index.php");
    exit();
}

// Update stock status
$queryf = mysqli_query($conn, "SELECT * FROM stock") or die(mysqli_error());
while($data = mysqli_fetch_array($queryf)){
    $amnt = $data['quantity'];
    $ida = $data['stock_id'];
    $status = ($amnt < 50) ? 'low' : 'enough';
    mysqli_query($conn, "UPDATE stock SET status='$status' WHERE stock_id=$ida") or die(mysqli_error());
}

// Get next customer ID
$SN = mysqli_query($conn, "SELECT 1+MAX(customer_id) FROM prescription");
$ind = mysqli_fetch_array($SN);
$idno = ($ind[0] == '') ? 10000 : $ind[0];
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
        
        .forme {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .forme p {
            margin-bottom: 0;
        }
        
        .forme input, .forme select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .forme input:focus, .forme select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(107, 115, 255, 0.2);
        }
        
        .forme input[type="submit"] {
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 500;
            grid-column: span 2;
            padding: 12px;
            margin-top: 10px;
        }
        
        .forme input[type="submit"]:hover {
            background: var(--secondary);
        }
        
        #viewer {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        
        #viewer2 {
            color: var(--dark);
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
            
            .forme {
                grid-template-columns: 1fr;
            }
            
            .forme input[type="submit"] {
                grid-column: span 1;
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
                <li><a href="pharmacist.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="prescription.php"><i class="fas fa-file-prescription"></i> Prescription</a></li>
                <li><a href="stock_pharmacist.php"><i class="fas fa-pills"></i> Stock</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>	
        </div>
    </div>
    
    <div id="main">
        <div id="tabbed_box" class="tabbed_box">  
            <h4>Prescription</h4> 
            <hr/>	
            <div class="tabbed_area">  
                <ul class="tabs">  
                    <li><a href="#content_1" class="active">VIEW PRESCRIPTIONS</a></li>  
                    <li><a href="#content_2">CREATE NEW</a></li>  
                </ul>  
                
                <div id="content_1" class="content active">  
                    <form action="" method="get">
                        <?php
                        $result = mysqli_query($conn, "SELECT DISTINCT * FROM prescription") or die(mysqli_error());
                        
                        echo "<table>";
                        echo "<thead><tr><th>Customer</th><th>Invoice No</th><th>Date</th><th>+Prescription</th><th>Delete</th></tr></thead>";
                        
                        while($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo '<td>' . $row['customer_name'] . '</td>';
                            echo '<td>' . $row['invoice_id'] . '</td>';
                            echo '<td>' . $row['date'] . '</td>';
                            echo '<td><a href="morepre.php?id='.$row['customer_id'].'"><i class="fas fa-plus-circle" style="color:var(--primary);font-size:1.2rem;"></i></a></td>';
                            echo '<td><a href="delete_prescription.php?id='.$row['invoice_id'].'"><i class="fas fa-trash-alt" style="color:var(--danger);font-size:1.2rem;"></i></a></td>';
                            echo "</tr>";
                        } 
                        echo "</table>";
                        ?>
                    </form>
                </div>  
                
                <div id="content_2" class="content">  
                    <div id="viewer"><span id="viewer2"></span></div>
                    <?php
                    $invNum = mysqli_query($conn, "SELECT 1+MAX(invoice_id) FROM invoice");
                    $invoice = mysqli_fetch_array($invNum);
                    $invoiceNo = ($invoice[0] == '') ? 10 : $invoice[0];
                    $_SESSION['invoice'] = $invoiceNo;
                    ?>
                    
                    <fieldset>
                        <legend>Customer Prescription</legend>
                        <form name="myform" onsubmit="return validateForm(this);" action="invoice.php" method="post" class="forme">
                            <p><input name="customer_id" placeholder="Customer ID" id="customer_id" type="text" required value="<?php echo $idno; ?>" /></p>
                            <p><input name="customer_name" placeholder="Customer Name" id="customer_name" type="text" required /></p>
                            <p><input name="phone" type="text" placeholder="Phone" id="phone" required /></p> 
                            <p>
                                <select class="form-control" name="drug_name" id="drug_name" required>
                                    <option value="">Select Drug</option>
                                    <?php
                                    $getpayType = mysqli_query($conn, "SELECT drug_name FROM stock");
                                    while($pType = mysqli_fetch_array($getpayType)) {
                                        echo "<option>".$pType['drug_name']."</option>";
                                    }
                                    ?>
                                </select>
                            </p>  
                            <p><input name="strength" type="text" id="strength" placeholder="Strength eg 100 mg" /></p>
                            <p><input name="dose" type="text" id="dose" placeholder="Dose eg 1x3" /></p>
                            <p><input name="quantity" type="text" id="quantity" placeholder="Quantity"/></p>
                            <p><input name="submit" type="submit" value="Submit"/></p>
                        </form>
                    </fieldset>
                </div>  
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
            
            // Tab switching
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
        });
        
        // Form validation
        function validateForm(form) {
            const value = form.customer_name.value;
            if(!value.match(/^[a-zA-Z]+(\s{1}[a-zA-Z]+)*$/)) {
                alert('Name Cannot contain numbers');
                return false;
            }
            return true;
        }
        
        // Drug selection AJAX
        $('#drug_name').change(function(){
            var package = $(this).val();
            $.ajax({
                type: 'POST',
                data: {package: package},
                url: 'get_strength.php',
                success: function(data){
                    $('#strength').val(data);
                }
            });
        });
        
        // Form field validation
        $(document).ready(function() {
            $("#drug_name,#strength,#dose,#quantity").change(function() {    
                var drug_name = $("#drug_name").val();
                var strength = $("#strength").val();
                var dose = $("#dose").val();
                var quantity = $("#quantity").val();
                
                if(drug_name.length && strength.length && dose.length && quantity.length > 0) {
                    $.ajax({
                        type: "POST", 
                        url: "check.php", 
                        data: 'drug_name='+drug_name+'&strength='+strength+'&dose='+dose+'&quantity='+quantity, 
                        success: function(msg) {
                            if(msg) {
                                $("#viewer2").html(msg);
                                $('#strength, #dose, #quantity').val('');
                                document.getElementById('drug_name').selectedIndex = 0;
                            }
                        }    
                    }); 
                }
            });
            
            $("#customer_id,#customer_name,#phone").change(function() {    
                var customer_id = $("#customer_id").val();
                var customer_name = $("#customer_name").val();
                var phone = $("#phone").val();
                
                if(customer_id.length && customer_name.length && phone.length > 0) {
                    $.ajax({
                        type: "POST", 
                        url: "check.php", 
                        data: 'customer_id='+customer_id+'&customer_name='+customer_name+'&phone='+phone, 
                        success: function(msg) {
                            if(msg) {
                                $("#viewer2").html(msg);
                            }
                        }    
                    }); 
                }
            });
        });
    </script>
</body>
</html>