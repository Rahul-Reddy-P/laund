<?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "stores";
        
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
        
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
        
            // Retrieve and sanitize form data
            $order_id = intval($_POST['order_id']);
            $card_number = $conn->real_escape_string($_POST['card_number']);
            $expiry_date = $conn->real_escape_string($_POST['expiry_date']);
            $cvv = $conn->real_escape_string($_POST['cvv']);
        
            // Dummy payment processing logic (in a real application, integrate with a payment gateway)
            $payment_success = true; // Simulate a successful payment
        
            if ($payment_success) {
                // Update payment status in the database
                $update_sql = "UPDATE orders SET payment_status='completed' WHERE id=$order_id";
        
                if ($conn->query($update_sql) === TRUE) {
                    echo "Payment successful. Your order ID: $order_id has been updated.";
                  
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "Payment failed. Please try again.";
            }
        
            // Close connection
            $conn->close();
        } else {
            echo "Invalid request method.";
        }
       
        ?>
  