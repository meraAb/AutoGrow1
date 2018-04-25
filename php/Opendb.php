<?php
    // Information about Connection
    $servername = "localhost";
    $username = "chris";
    $password = "cdelis1994";
    $dbname = "precision_agriculture";
    
    // Connect to DataBase
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
       
    mysqli_set_charset($conn,"utf8");
?>