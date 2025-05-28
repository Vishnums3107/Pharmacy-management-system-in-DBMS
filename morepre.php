<?php
session_start();
if(isset($_GET['id'])){
    $userid=$_GET['id'];
}
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

$quryfetch=mysqli_query($conn, "SELECT* FROM prescription WHERE customer_id='".$userid."'")or die(mysqli_error());
$rw=mysqli_num_rows($quryfetch);
if($rw>0){
    while($dta=mysqli_fetch_array($quryfetch)){
        $name=$dta['customer_name'];
        $invno=$dta['invoice_id'];
        $phone=$dta['phone'];
        $prescr=$dta['prescription_id'];
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
        <h1><img src="images/hd_logo.jpg" class="img"> Pharmacy Management System</h1>
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
                    <li><a href="#content_1" class="active">Prescribe more drugs</a></li>  
                </ul>  
                
                <div id="content_1" class="content">  
                    <fieldset>
                        <legend>More prescription</legend>
                        <form name="myform" onsubmit="return validateForm(this);" action="" method="post" class="forme">
                            <p><input name="customer_id" placeholder="Customer ID" id="customer_id" type="text" required value="<?php echo $userid; ?>" /></p>
                            <p><input name="customer_name" placeholder="Customer Name" id="customer_name" type="text" required value="<?php echo $name; ?>" /></p>
                            <p><input name="phone" type="text" placeholder="Phone" id="phone" required value="<?php echo $phone; ?>" /></p> 
                            <p>
                                <select name="drug_name" id="drug_name" placeholder="Select drug">
                                    <?php
                                    $getpayType=mysqli_query($conn, "SELECT drug_name FROM stock");
                                    while($pType=mysqli_fetch_array($getpayType)) {
                                        echo "<option>".$pType['drug_name']."</option>";
                                    }
                                    ?>
                                </select>
                            </p>  
                            <p><input name="stred" type="text" id="strength" placeholder="Strength eg 100 mg" /></p>
                            <p><input name="dose" type="text" id="dose" placeholder="Dose eg 1x3" required /></p>
                            <p><input name="quantity" type="text" id="quantity" placeholder="Quantity" required/></p>
                            <input type="hidden" value="<?php echo $invno ?>" name="inv">
                            <p><input name="submity" type="submit" value="Submit"/></p>
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
        });
        
        // Form validation
        function validateForm(form) {
            var value = form.customer_name.value;
            if(value.match(/^[a-zA-Z]+(\s{1}[a-zA-Z]+)*$/)) {
                return true;
            } else {
                alert('Name Cannot contain numbers');
                return false;
            }
        }
        
        // AJAX for drug strength
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
    </script>
    
    <?php
    if(isset($_POST['submity'])){
        $dname=$_POST['drug_name'];
        $star=$_POST['stred'];
        $qua=$_POST['quantity'];
        $doz=$_POST['dose'];
        $incv=$_POST['inv'];
        $month=date('F');
        $yr=date('Y');
        $day=date('j');
        
        $querysql=mysqli_query($conn, "INSERT INTO tempprescri(customer_id,customer_name,phone,drug_name,strength,dose,quantity)
                        VALUES('{$userid}','{$name}','{$phone}','{$dname}','{$star}','{$doz}','{$qua}')") or die(mysqli_error());
                
        $get_cost=mysqli_query($conn, "SELECT cost FROM stock WHERE drug_name='{$dname}'");
        $cost=mysqli_fetch_array($get_cost);
        $tota=$qua*$cost[0];
        
        $file=fopen("receipts/docs/".$userid.".txt", "a+");
        fwrite($file, $dname.";".$star.";".$doz.";".$qua.";".$cost[0].";".$tota."\n");
        fclose($file);
        
        $sqlI=mysqli_query($conn, "INSERT INTO invoice(invoice_id, customer_name,served_by,status)
                VALUES('{$incv}','{$name}','{$user}','Pending') ");
        $slI=mysqli_query($conn, "INSERT INTO ids(ids,invoice_id)
                VALUES('{$userid}','{$incv}') ") or die(mysqli_error());
        $getDetails=mysqli_query($conn, "SELECT * FROM tempprescri WHERE customer_id='{$userid}'");
        
        while($item1=mysqli_fetch_array($getDetails)) {	
            $getDetails1=mysqli_query($conn, "SELECT stock_id, cost FROM stock WHERE drug_name='{$item1['drug_name']}'");	
            $details=mysqli_fetch_array($getDetails1);
            $sqlId=mysqli_query($conn, "INSERT INTO invoice_details(invoice,drug,cost,quantity,day,month,year)
                    VALUES('{$incv}','{$details['stock_id']}','{$details['cost']}','{$item1['quantity']}','$day','$month','$yr')");
            $count[]=$details['cost']*$item1['quantity'];
    
            $getDetailZ=mysqli_query($conn,"SELECT * FROM tempprescri WHERE customer_id='{$userid}'");
            while($item12=mysqli_fetch_array($getDetailZ)) {	
                $getDetails12=mysqli_query($conn, "SELECT * FROM stock WHERE drug_name='{$item12['drug_name']}'");	
                $details2=mysqli_fetch_array($getDetails12);
                $sqlIp=mysqli_query($conn, "INSERT INTO prescription_details(pres_id,drug_name,strength,dose,quantity)
                        VALUES('{$prescr}','{$details2['stock_id']}','{$item12['strength']}','{$item12['dose']}','{$item12['quantity']}')");	
            }					
        }
          
        $newquant=$details2['quantity']-$qua;
        if($newquant!=0){
            $update=mysqli_query($conn,"Update stock set quantity='{$newquant}' where stock_id='".$details2['stock_id']."'") or die(mysqli_error());
        }
        
        $sqy=mysqli_query($conn, "INSERT INTO sales(invoice,drug,cost,quantity,day,month,year)
                VALUES('{$incv}','{$details['stock_id']}','{$details['cost']}','$qua','$day','$month','$yr')"); 
        
        if($sqlIp){
            echo '<script type="text/javascript">'; 
            echo 'alert("Drug successfully added");'; 
            echo 'window.location.href = "prescription.php";';
            echo '</script>';
        }
        unset($_POST);
    }
    ?>
</body>
</html>