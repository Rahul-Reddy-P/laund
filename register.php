<!-- register.php -->
<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];

    $sql = "INSERT INTO Users (FirstName, LastName, Email, PasswordHash, PhoneNumber, Address) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $password, $phoneNumber, $address);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header('Location: login.html');
exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
