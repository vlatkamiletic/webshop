<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Provjeri korisnika u bazi
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Provjeri lozinku
        if (password_verify($password, $user['password'])) {
            // Lozinka je ispravna, spremi korisnika u sesiju
            $_SESSION['user'] = $user;
            header('Location: index.php'); // Redirektiraj korisnika na početnu stranicu
        } else {
            echo "Pogrešna lozinka.";
        }
    } else {
        echo "Korisnik s tim emailom ne postoji.";
    }
}
?>
