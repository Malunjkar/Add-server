<?php
session_start();
include_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $adname = $_POST['adname'];
    $payment = $_POST['payment'];

    // Fetch bank details from client_accounts table
    $stmt = $conn->prepare("SELECT account_number, bank_name, ifsc_code, account_name FROM client_accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($account_number, $bank_name, $ifsc_code, $account_name);
    $stmt->fetch();
    $stmt->close();

    if ($account_number && $bank_name && $ifsc_code && $account_name) {
        // Here you would add your logic to process the payment using a payment gateway API
        // For example, integrating with a payment gateway like Stripe or PayPal

        // Mock payment processing
        $payment_successful = true; // Change this based on actual API response

        if ($payment_successful) {
            // Insert record into payments table
            $stmt = $conn->prepare("INSERT INTO payments (email, adname, payment) VALUES (?, ?, ?)");
            $stmt->bind_param("ssd", $email, $adname, $payment);

            if ($stmt->execute()) {
                echo "Payment processed successfully and deposited to your bank account.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } 
    } else {
        echo "Client bank details not found.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
