<?php
// Database connection
$host = "localhost";
$dbname = "stores";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Function to calculate distance using Haversine formula
function distance($lat1, $lon1, $lat2, $lon2, $unit = 'km') {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "KM") {
        return ($miles * 1.609344);
    } else if ($unit == "MI") {
        return $miles;
    } else {
        return $miles * 1.609344;
    }
}

// Get user's latitude and longitude from form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_latitude = $_POST['latitude'];
    $user_longitude = $_POST['longitude'];

    // Prepare SQL query to fetch stores and calculate distance
    $stmt = $pdo->prepare("SELECT id, name, address, latitude, longitude FROM stores");
    $stmt->execute();
    $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate and display nearby stores within 10km
    echo "<h2>Nearby Stores</h2>";
    echo "<ul>";
    foreach ($stores as $store) {
        $store_latitude = $store['latitude'];
        $store_longitude = $store['longitude'];
        $store_distance = distance($user_latitude, $user_longitude, $store_latitude, $store_longitude);

        if ($store_distance <= 10) { // Change 10 to the desired distance in kilometers
            echo "<li>";
            echo "<h3>{$store['name']}</h3>";
            echo "<p><strong>Address:</strong> {$store['address']}</p>";
            echo "<p><strong>Distance:</strong> " . round($store_distance, 2) . " km</p>";
            echo "</li>";

        }
    }
    echo "</ul>";
}
else {
    echo "No nearby stores found.";
}
?>
