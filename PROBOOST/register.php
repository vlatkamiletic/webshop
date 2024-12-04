<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php';

    if (!$conn) {
        $_SESSION['error_message'] = "Greška s bazom podataka.";
        header("Location: index.php"); // Redirect to index.php
        exit;
    }

    // Dohvati podatke iz POST zahtjeva
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validacija unosa
    if (empty($name) || empty($surname) || empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Sva polja su obavezna!";
        header("Location: index.php"); // Redirect to index.php
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Neispravan email format!";
        header("Location: index.php"); // Redirect to index.php
        exit;
    }

    // Provjera postoji li već korisnik s tim emailom
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        $_SESSION['error_message'] = "Greška u pripremi upita.";
        header("Location: index.php"); // Redirect to index.php
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "Email već postoji!";
        $stmt->close();
        header("Location: index.php"); // Redirect to index.php
        exit;
    }
    $stmt->close();

    // Hashiraj lozinku
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if (!$hashed_password) {
        $_SESSION['error_message'] = "Greška pri hashiranju lozinke.";
        header("Location: index.php"); // Redirect to index.php
        exit;
    }

    // Spremanje korisnika u bazu
    $stmt = $conn->prepare("INSERT INTO users (name, surname, email, password, role) VALUES (?, ?, ?, ?, 'user')");
    if (!$stmt) {
        $_SESSION['error_message'] = "Greška u pripremi upita.";
        header("Location: index.php"); // Redirect to index.php
        exit;
    }

    $stmt->bind_param("ssss", $name, $surname, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $name;
        $_SESSION['success_message'] = "Registracija uspješna!";
        header("Location: index.php"); // Redirect to index.php
        exit();
    } else {
        $_SESSION['error_message'] = "Greška pri registraciji!";
    }

    $stmt->close();
    $conn->close();

    // Redirect to index.php in case of an error
    header("Location: index.php");
    exit;
}
?>