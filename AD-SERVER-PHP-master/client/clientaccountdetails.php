<?php 
session_start();
$email = $_SESSION['email'];

// Database connection
$username = 'root';
$password = '';
$hostname = 'localhost:4306';
$databasename='ad_server';

$conn = new mysqli($hostname, $username, $password, $databasename);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_POST['account_number'];
    $bank_name = $_POST['bank_name'];
    $ifsc_code = $_POST['ifsc_code'];
    $account_name = $_POST['account_name'];

    $stmt = $conn->prepare("INSERT INTO client_accounts (email, account_number, bank_name, ifsc_code, account_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $email, $account_number, $bank_name, $ifsc_code, $account_name);

    if ($stmt->execute()) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Client Panel</title>
    <!-- Bootstrap Core CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="application/x-javascript"> 
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); 
        function hideURLbar(){ window.scrollTo(0,1); } 
    </script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/lines.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="../js/jquery.min.js"></script>
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../css/custom.css" rel="stylesheet">
    <script src="../js/metisMenu.min.js"></script>
    <script src="../js/custom.js"></script>
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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $email ?> <i class="fa fa-user"></i><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class="m_2"><a href="logout.php"><i class="fa fa-lock"></i> Logout</a></li>  
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <?php include 'sidebar.php' ?>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome Client
                        </h1>
                        <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
                        <fieldset>
                            <form action="" method="POST">
                                <label for="card">Account Number:</label>
                                <input type="text" name="account_number" maxlength="10" required class="form-control1 ng-invalid ng-invalid-required ng-touched">
                                <br>
                                <label for="expiry">Banks:</label>
                                <select name="bank_name" id="bank" class="form-control1 ng-invalid ng-invalid-required ng-touched">
                                    <option disabled selected>Select Your Bank</option>
                                    <option value="State Bank Of India">State Bank Of India</option>
                                    <option value="ICICI Bank">ICICI Bank</option>
                                    <option value="Vijaya Bank">Vijaya Bank</option>
                                    <option value="Bank of Baroda">Bank of Baroda</option>
                                    <option value="Yes Bank">Yes Bank</option>
                                    <option value="Central Bank of India">Central Bank of India</option>
                                </select>
                                <br>
                                <label for="cvv">IFSC Code</label>
                                <input type="text" name="ifsc_code" maxlength="11" required class="form-control1 ng-invalid ng-invalid-required ng-touched">
                                <br>
                                <label for="name">Account Name</label>
                                <input type="text" name="account_name" required class="form-control1 ng-invalid ng-invalid-required ng-touched">
                                <br><br>
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

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
