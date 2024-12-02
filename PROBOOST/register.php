<?php
// Uključi db.php za povezivanje s bazom
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Dohvati podatke iz forme
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['regEmail'];
    $password = $_POST['regPassword'];

    // Provjera jesu li svi podaci uneseni
    if (empty($name) || empty($surname) || empty($email) || empty($password)) {
        echo "Sva polja moraju biti ispunjena!";
        exit;
    }

    // Sigurnost: hashiranje lozinke
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Provjeri postoji li već korisnik s tim emailom
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Korisnik s tim emailom već postoji!";
        exit;
    }

    // Spremi novog korisnika u bazu
    $insertQuery = "INSERT INTO users (name, surname, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssss", $name, $surname, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Pokreni sesiju
        session_start();

        // Pohrani korisničke podatke u sesiju (npr. email ili korisnički ID)
        $_SESSION['user'] = $email;  // Možete pohraniti samo email ili ID, ovisno o vašim potrebama

        // Preusmjeri korisnika na početnu stranicu
        header("Location: index.php");
        exit;
    } else {
        echo "Došlo je do greške: " . $conn->error;
        exit;
    }
}
?>
