<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $numberOfMembers = $_POST["numberOfMembers"];
    $cardHolderName = $_POST["cardHolderName"];
    $from = $_POST["from"];
    $email = $_POST["email"];
    $fareType = $_POST["t"];
    $flightNumber = $_POST["flightNumber"];
    $to = $_POST["to"];
    $seatNumbers = $_POST["seats"];
    $totalAmount = $_POST["Price"];



    //database connection
    $conn = new mysqli('localhost','root','','aviate');
    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        if (isset($_POST["t"]) && !empty($_POST["t"])) {
        $selectedtype = $_POST["t"];
        }
        $stmt = $conn->prepare("insert into payment(Number_Of_Members,CardHolder_Name,From_Airport,To_Airport,Flight_Number,Fare_Type,Email,Seats,Price)
        values(?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("issssssss",$numberOfMembers,$cardHolderName,$from,$to,$flightNumber,$selectedtype,$email,$seatNumbers,$totalAmount);
        $stmt->execute();
        header("Location: success.html");
        $stmt->close();
        $conn->close();
        
        exit;
}

?>

</body>
</html>