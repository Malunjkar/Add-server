<?php 
session_start();
$email = $_SESSION['email'];

// Check if the form is submitted
if(isset($_POST['s1'])) {
    // Retrieve form data
    $cardNumber = $_POST['card'];
    $expiryMonth = $_POST['m1'];
    $expiryYear = $_POST['y1'];
    $cvv = $_POST['cvv'];
    $nameOnCard = $_POST['name'];
    $adname = $_POST['adname']; // New: Fetch adname from form
    $advertiser = $_POST['advertiser']; // New: Fetch advertiser name from form

    // Fetch payment amount from the viewuploadedads.php file
    include_once "../config.php";
    $select = "SELECT amount FROM new_ad WHERE adname = ? AND email = ?";
    if ($stmt = mysqli_prepare($conn, $select)) {
        mysqli_stmt_bind_param($stmt, "ss", $adname, $advertiser);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $paymentAmount = $row['amount'];
        } else {
            $paymentAmount = 0; // Default value if adname not found
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    // Database connection and query execution
    $query = "INSERT INTO advertiser_payments (advertiser_email, card_number, expiry_month, expiry_year, cvv, name_on_card, payment_amount) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    // Bind parameters
    $stmt->bind_param("ssssssd", $advertiser, $cardNumber, $expiryMonth, $expiryYear, $cvv, $nameOnCard, $paymentAmount);
    
    // Execute the statement
    if($stmt->execute()) {
        // Payment record inserted successfully
        // You can redirect or show a success message here
        echo "Payment record inserted successfully.";
    } else {
        // Error in inserting payment record
        // You can handle the error accordingly
        echo "Error: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel='stylesheet' type='text/css' />
    <!-- Graph CSS -->
    <link href="../css/lines.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet"> 
    <!-- jQuery -->
    <script src="../js/jquery.min.js"></script>
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Nav CSS -->
    <link href="../css/custom.css" rel="stylesheet">
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../js/metisMenu.min.js"></script>
    <script src="../js/custom.js"></script>
    <!-- Graph JavaScript -->
    <script src="../js/d3.v3.js"></script>
    <script src="../js/rickshaw.js"></script>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.php">Adserver</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $email?> <i class="fa fa-user"></i><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class="m_2"><a href="logout.php"><i class="fa fa-lock"></i> Logout</a></li>  
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <?php include 'sidebar.php' ?>
        </nav>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Payment</h1>
                        <fieldset>
                            <form action="" method="POST">
                                <label for="advertiser">Advertiser:</label>
                                <select name="advertiser" class="form-control1">
                                    <option value="<?php echo $email ?>"><?php echo $email ?></option> <!-- Assuming the current user is the advertiser -->
                                    <!-- You may populate the list with advertisers from your database -->
                                </select>
                                <br>
                                <label for="adname">Advertisement Name:</label>
                                <input type="text" name="adname" required class="form-control1 ng-invalid ng-invalid-required ng-touched" ng-model="model.name">
                                <br>
                                <!-- Other form fields remain the same -->
                                <label for="card">Card Number:</label>
                                <input type="text" name="card" accept="number" maxlength="16" required class="form-control1 ng-invalid ng-invalid-required ng-touched" ng-model="model.name">
                                <br>
                                <label for="expiry">Expiry:</label>
                                <select name="m1" id="month" class="form-control1 ng-invalid ng-invalid-required ng-touched" ng-model="model.name">
                                    <option disabled selected>Month</option>
                                    <option value="Jan">Jan</option>
                                    <option value="Feb">Feb</option>
                                    <option value="Mar">Mar</option>
                                    <option value="Apr">Apr</option>
                                    <option value="May">May</option>
                                    <option value="Jun">Jun</option>
                                    <option value="July">July</option>
                                    <option value="Aug">Aug</option>
                                    <option value="Sept">Sept</option>
                                    <option value="Oct">Oct</option>
                                    <option value="Nov">Nov</option>
                                    <option value="Dec">Dec</option>
                                    <!-- Other month options -->
                                </select>
                                <select name="y1" id="year" class="form-control1 ng-invalid ng-invalid-required ng-touched" ng-model="model.name">
                                    <option disabled selected>Year</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>

                                    <!-- Other year options -->
                                </select>
                                <br>
                                <label for="cvv">CVV:</label>
                                <input type="text" name="cvv" accept="number" maxlength="3" required class="form-control1 ng-invalid ng-invalid-required ng-touched" ng-model="model.name">
                                <br>
                                <label for="name">Name On Card:</label>
                                <input type="text" name="name" required class="form-control1 ng-invalid ng-invalid-required ng-touched" ng-model="model.name">
                                <br>
                                <input type="submit" value="Submit" name="s1" class="btn btn-primary">
                            </form>
                        </fieldset>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
</body>
</html>
