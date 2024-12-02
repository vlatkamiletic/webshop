<?php
session_start();
session_destroy(); // Uništite sesiju
header('Location: index.php'); // Redirektirajte korisnika na početnu stranicu
exit();
?>
