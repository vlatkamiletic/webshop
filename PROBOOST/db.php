<?php
$host = "localhost";
$dbname = "webshop";
$username = "root";
$password = "";

// Poveži se na bazu podataka
$conn = new mysqli($host, $username, $password, $dbname);

// Provjeri povezanost
if ($conn->connect_error) {
    die("Greška pri povezivanju na bazu: " . $conn->connect_error);
}
?>
