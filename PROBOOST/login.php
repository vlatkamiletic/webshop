<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php'; // Povezivanje na bazu

    // Dohvati podatke iz forme
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Pripremi SQL upit
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // "s" znači da se očekuje string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            
            // Preusmjeravanje na početnu stranicu
            header("Location: index.php");
            exit;
        } else {
            echo "Pogrešna lozinka.";
        }
    } else {
        echo "Korisnik s ovim emailom ne postoji.";
    }

    $stmt->close();
    $conn->close();
}
?>
