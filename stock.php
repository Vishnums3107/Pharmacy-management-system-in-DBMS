<?php
include_once('connect_db.php');
session_start();
$fname = $_SESSION['first_name'];
$lname = $_SESSION['last_name'];

// Update stock status
$queryf = mysqli_query($conn, "SELECT * FROM stock") or die(mysqli_error());
while($data = mysqli_fetch_array($queryf)) {
    $amnt = $data['quantity'];
    $ida = $data['stock_id'];
    $status = ($amnt < 50) ? 'low' : 'enough';
    mysqli_query($conn, "UPDATE stock SET status='$status' WHERE stock_id=$ida") or die(mysqli_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Management System</title>
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
            flex-wrap: wrap;
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
        
        .status-low {
            color: var(--danger);
            font-weight: 500;
        }
        
        .status-enough {
            color: var(--success);
            font-weight: 500;
        }
        
        .action-icon {
            color: var(--dark);
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        
        .action-icon:hover {
            color: var(--primary);
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
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: var(--secondary);
        }
        
        .result {
            position: absolute;
            background: white;
            width: 170px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            z-index: 10;
        }
        
        .result p {
            padding: 10px;
            margin: 0;
            cursor: pointer;
        }
        
        .result p:hover {
            background-color: #f5f5f5;
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
            
            .tabs {
                flex-direction: column;
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
                <li><a href="manager.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="view.php"><i class="fas fa-users"></i> View Users</a></li>
                <li><a href="view_prescription.php"><i class="fas fa-file-prescription"></i> View Prescription</a></li>
                <li><a href="stock.php" class="active"><i class="fas fa-pills"></i> Manage Stock</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>	
        </div>
    </div>
    
    <div id="main">
        <div id="tabbed_box" class="tabbed_box">  
            <h4>Manage Stock</h4> 
            <hr/>	
            <div class="tabbed_area">  
                <ul class="tabs">  
                    <li><a href="#content_1" class="active">VIEW STOCK</a></li>  
                    <li><a href="#content_2">ADD STOCK</a></li>
                    <li><a href="#content_3">UPDATE PRICE</a></li>
                    <li><a href="#content_4">ADD MORE STOCK</a></li>
                </ul>  
                
                <div id="content_1" class="content active">  
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM stock") or die(mysqli_error());
                    
                    echo "<table>";
                    echo "<thead><tr><th>Name</th><th>Available Stock</th><th>Description</th><th>Status</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    
                    while($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo '<td>' . $row['drug_name'] . '</td>';
                        echo '<td>' . $row['quantity'] . ' ' . $row['category'] . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '<td class="status-' . $row['status'] . '">' . ucfirst($row['status']) . '</td>';
                        echo '<td><a href="delete_stock.php?id='.$row['stock_id'].'"><i class="fas fa-trash-alt action-icon"></i></a></td>';
                        echo "</tr>";
                    } 
                    echo "</tbody>";
                    echo "</table>";
                    ?> 
                </div>  
                
                <div id="content_2" class="content">  
                    <fieldset>
                        <legend>Add New Stock</legend>
                        <form name="myform" onsubmit="return validateForm(this);" action="stock.php" method="post">
                            <div class="form-group">
                                <label for="samm">Drug Name</label>
                                <input type="text" class="form-control" name="drug_name" id="samm" placeholder="Drug Name" required>
                                <div class="result"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="company">Manufacturing Company</label>
                                <input type="text" class="form-control" name="company" id="company" placeholder="Manufacturing Company" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <input type="text" class="form-control" name="supplier" id="supplier" placeholder="Supplier" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="strength">Strength</label>
                                <input type="text" class="form-control" name="stren" id="strength" placeholder="Strength" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="text" class="form-control" name="quantizz" id="quantity" placeholder="Quantity eg 100 tablet or 20 bottles" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="cost">Unit Cost</label>
                                <input type="text" class="form-control" name="cost" id="cost" placeholder="Unit Cost" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="drugname">Category</label>
                                <select class="form-control" name="cate" id="drugname" required>
                                    <option value="">Select category</option>
                                    <?php
                                    $getpayType = mysqli_query($conn, "SELECT category FROM drugtype");
                                    while($pType = mysqli_fetch_array($getpayType)) {
                                        echo "<option>".$pType['category']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="druname">Description</label>
                                <select class="form-control" name="des" id="druname" required>
                                    <option value="">Select description</option>
                                    <?php
                                    $getpayType = mysqli_query($conn, "SELECT description FROM drugtype");
                                    while($pType = mysqli_fetch_array($getpayType)) {
                                        echo "<option>".$pType['description']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <button type="submit" name="submiti" class="btn">Submit</button>
                        </form>
                    </fieldset>
                </div>  
                
                <div id="content_3" class="content">  
                    <fieldset>
                        <legend>Update Price</legend>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="sammy">Select Drug</label>
                                <select class="form-control" name="dets" id="sammy" required>
                                    <option value="">Select drug</option>
                                    <?php
                                    $getpayType = mysqli_query($conn, "SELECT * FROM stock");
                                    while($pType = mysqli_fetch_array($getpayType)) {
                                        echo "<option>".$pType['drug_name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="costz">Current Price</label>
                                <input type="text" class="form-control" name="oldprice" id="costz" placeholder="Current price" readonly required>
                            </div>
                            
                            <div class="form-group">
                                <label for="newprice">New Price</label>
                                <input type="text" class="form-control" name="newprice" id="newprice" placeholder="New price" required>
                            </div>
                            
                            <button type="submit" name="updat" class="btn">Update</button>
                        </form>
                    </fieldset>
                    
                    <?php
                    if(isset($_POST['updat'])){
                        $oldp = $_POST['oldprice'];
                        $newp = $_POST['newprice'];
                        $dn = $_POST['dets'];
                        
                        if($dn != ''){
                            $queryd = mysqli_query($conn,"SELECT * FROM stock WHERE drug_name='".$dn."'") or die(mysqli_error());
                            $rowa = mysqli_num_rows($queryd);
                            
                            if($rowa > 0){
                                while($dat = mysqli_fetch_array($queryd)){
                                    $idt = $dat['stock_id'];
                                }
                                
                                $queryupda = mysqli_query($conn,"UPDATE stock SET cost='".$newp."' WHERE stock_id='".$idt."'") or die(mysqli_error());
                                
                                if($queryupda){
                                    echo '<script type="text/javascript">'; 
                                    echo 'alert("Price successfully updated.");'; 
                                    echo 'window.location.href = "stock.php";';
                                    echo '</script>';
                                } else {
                                    echo '<script type="text/javascript">'; 
                                    echo 'alert("Failed. Please try again");'; 
                                    echo '</script>';
                                }
                            } else {
                                echo '<script>window.alert("No data found")</script>';
                            }
                        } else {
                            echo '<script>window.alert("Please select a drug")</script>';
                        }
                    }
                    ?>
                </div>
                
                <div id="content_4" class="content">
                    <fieldset>
                        <legend>Add More Stock</legend>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="samj">Select Drug</label>
                                <select class="form-control" name="detsg" id="samj" required>
                                    <option value="">Select drug</option>
                                    <?php
                                    $getpayType = mysqli_query($conn, "SELECT * FROM stock");
                                    while($pType = mysqli_fetch_array($getpayType)) {
                                        echo "<option>".$pType['drug_name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="stockz">Current Stock</label>
                                <input type="text" class="form-control" name="orginalstock" id="stockz" placeholder="Current stock" readonly required>
                            </div>
                            
                            <div class="form-group">
                                <label for="addstock">Amount to Add</label>
                                <input type="text" class="form-control" name="newstock" id="addstock" placeholder="Amount to add" required>
                            </div>
                            
                            <button type="submit" name="add" class="btn">Add Stock</button>
                        </form>
                    </fieldset>
                    
                    <?php
                    if(isset($_POST['add'])){
                        $olds = $_POST['orginalstock'];
                        $news = $_POST['newstock'];
                        $dndg = $_POST['detsg'];
                        $final = $olds + $news;
                        
                        if($dndg != ''){
                            $queryd = mysqli_query($conn,"SELECT * FROM stock WHERE drug_name='".$dndg."'") or die(mysqli_error());
                            $rowa = mysqli_num_rows($queryd);
                            
                            if($rowa > 0){
                                while($dat = mysqli_fetch_array($queryd)){
                                    $idtd = $dat['stock_id'];
                                }
                                
                                $queryupdua = mysqli_query($conn,"UPDATE stock SET quantity='".$final."' WHERE stock_id='".$idtd."'") or die(mysqli_error());
                                
                                if($queryupdua){
                                    echo '<script type="text/javascript">'; 
                                    echo 'alert("Stock successfully updated.");'; 
                                    echo 'window.location.href = "stock.php";';
                                    echo '</script>';
                                } else {
                                    echo '<script type="text/javascript">'; 
                                    echo 'alert("Failed. Please try again");'; 
                                    echo '</script>';
                                }
                            } else {
                                echo '<script>window.alert("No data found")</script>';
                            }
                        } else {
                            echo '<script>window.alert("Please select a drug")</script>';
                        }
                    }
                    
                    // Update stock status after any changes
                    $queryf = mysqli_query($conn, "SELECT * FROM stock") or die(mysqli_error());
                    while($data = mysqli_fetch_array($queryf)) {
                        $amnt = $data['quantity'];
                        $ida = $data['stock_id'];
                        $status = ($amnt < 50) ? 'low' : 'enough';
                        mysqli_query($conn, "UPDATE stock SET status='$status' WHERE stock_id=$ida") or die(mysqli_error());
                    }
                    ?>
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
            
            // Tab switching functionality
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
        
        // Drug search functionality
        $(document).ready(function(){
            $('.content #samm').on("keyup input", function(){
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".result");
                
                if(inputVal.length){
                    $.get("search_med.php", {term: inputVal}).done(function(data){
                        resultDropdown.html(data);
                    });
                } else {
                    resultDropdown.empty();
                }
            });
            
            // Set search input value on click of result item
            $(document).on("click", ".result p", function(){
                $(this).parents(".form-group").find('#samm').val($(this).text());
                $(this).parent(".result").empty();
            });
        });
        
        // Get current price when drug is selected
        $('#sammy').change(function(){
            var package = $(this).val();
            $.ajax({
                type: 'POST',
                data: {package: package},
                url: 'get_cost.php',
                success: function(data){
                    $('#costz').val(data);
                }
            });
        });
        
        // Get current stock when drug is selected
        $('#samj').change(function(){
            var package = $(this).val();
            $.ajax({
                type: 'POST',
                data: {package: package},
                url: 'get_stock.php',
                success: function(data){
                    $('#stockz').val(data);
                }
            });
        });
        
        // Form validation
        function validateForm(form) {
            const category = form.cate.value;
            const description = form.des.value;
            
            if(category === "Select category" || description === "Select description") {
                alert("Please select both category and description");
                return false;
            }
            return true;
        }
    </script>
    
    <?php
    if(isset($_POST['submiti'])){
        $strades = "Select category";
        $stracat = "Select description";
        $sname = $_POST['drug_name'];
        $cat = $_POST['cate'];
        $des = $_POST['des'];    
        $com = $_POST['company'];
        $stren = $_POST['stren'];
        $sup = $_POST['supplier'];
        $qua = $_POST['quantizz'];
        $cost = $_POST['cost'];
        
        if($des != $strades && $cat != $stracat){
            $sql = mysqli_query($conn, "INSERT INTO stock(drug_name,category,description,company,supplier,strength,quantity,cost,status,date_supplied)
                    VALUES('$sname','$des','$cat','$com','$sup','$stren','$qua','$cost','enough',NOW())");
            
            if($sql > 0) {
                echo '<script type="text/javascript">'; 
                echo 'alert("Drug successfully added.");'; 
                echo 'window.location.href = "stock.php";';
                echo '</script>';
            } else {
                $message1 = "<font color=red>Failed, Try again</font>";
            }
        } else {
            echo '<script>window.alert("Ooops! Description and category fields cannot be submitted while empty.")</script>';
        }
    }
    ?>
</body>
</html>