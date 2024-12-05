<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">PROBOOST</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'proteini') ? 'active' : ''; ?>" href="products.php?category=proteini">Proteini</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'vitamini') ? 'active' : ''; ?>" href="products.php?category=vitamini">Vitamini</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'snacks') ? 'active' : ''; ?>" href="products.php?category=snacks">Snacks</a>
                </li>
            </ul>

            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Odjavi se</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#signInModal">Prijava</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#cartModal">Košarica</a>
                </li>
            </ul>
        </div>
    </div>
</nav>