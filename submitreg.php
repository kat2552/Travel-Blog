<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'form';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if(mysqli_connect_error()) {
    exit('Error connecting to the database: ' . mysqli_connect_error());
}

if(empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email'])) {
    exit('Empty Field(s)');
}

$stmt = $con->prepare('SELECT firstName, password FROM users WHERE lastName = ?');
$stmt->bind_param('s', $_POST['lastName']);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0) {
    echo 'Username already exists. Try Again';
} else {
    $stmt = $con->prepare('INSERT INTO users (lastName, email, username) VALUES (?, ?, ?)');
    $email = hash('sha256', $_POST['email']);
    $stmt->bind_param('sss', $_POST['lastName'], $email, $_POST['username']);
    if($stmt->execute()) {
        echo 'Successfully Registered';
    } else {
        echo 'Error: ' . $stmt->error; 
    }
}

$stmt->close();
$con->close();
?>
