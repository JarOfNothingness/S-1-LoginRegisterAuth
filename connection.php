<?php 
    $host = "localhost";
    $username = "root";
    $password = "12345";
    $databaseNimuDiri = "grading management";
    $connection = mysqli_connect($host,$username,$password,$databaseNimuDiri);
    if($connection != true){
        die("Error");
    }

?>