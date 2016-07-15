<?php 
    $mysqli = mysqli_connect("localhost", "root", "toor");
    mysqli_query($mysqli,"CREATE USER IF NOT EXISTS 'homestead'@'localhost' IDENTIFIED BY 'secret';");
    mysqli_query($mysqli,"GRANT ALL PRIVILEGES ON * . * TO 'homestead'@'localhost';");
    mysqli_query($mysqli,"CREATE DATABASE IF NOT EXISTS homestead;");
    mysqli_close($mysqli);
?>