<?php
// Function to calculate distance between two points using Haversine formula
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $R = 6371; // Radius of the Earth in km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $R * $c; // Distance in km
    return $distance;
}

// Function to find the nearest store based on user's location
function findNearestStore($userLat, $userLon, $conn) {
    $nearestStore = null;
    $minDistance = PHP_FLOAT_MAX;

    // Query to get all stores
    $sql = "SELECT id, name, latitude, longitude FROM stores";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $storeLat = $row['latitude'];
            $storeLon = $row['longitude'];
            $distance = calculateDistance($userLat, $userLon, $storeLat, $storeLon);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearestStore = $row;
            }
        }
    }
    return $nearestStore;
}

// Main script to handle order placement
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
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $order_details = $conn->real_escape_string($_POST['order_details']);
    $userLatitude = floatval($_POST['latitude']);
    $userLongitude = floatval($_POST['longitude']);

    // Find the nearest store
    $nearestStore = findNearestStore($userLatitude, $userLongitude, $conn);

    if ($nearestStore) {
        $store_id = $nearestStore['id'];

        // Insert order into database
        $order_sql = "INSERT INTO orders (store_id, name, email, phone, order_details) 
                      VALUES ($store_id, '$name', '$email', '$phone', '$order_details')";

        if ($conn->query($order_sql) === TRUE) {
            $order_id = $conn->insert_id;
            echo "Order placed successfully with Order ID: $order_id";
        } else {
            echo "Error: " . $order_sql . "<br>" . $conn->error;
        }
    } else {
        echo "No nearby stores found.";
    }

    // Close connection
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
