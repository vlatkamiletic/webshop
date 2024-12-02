<?php
$servername = "localhost";
$username = "root"; // Ili korisničko ime za vašu bazu
$password = ""; // Lozinka za vašu bazu
$dbname = "webshop"; // Naziv vaše baze podataka

$conn = new mysqli($servername, $username, $password, $dbname);

// Provjerite povezanost
if ($conn->connect_error) {
    die("Ne mogu se spojiti na bazu: " . $conn->connect_error);
}
?>
