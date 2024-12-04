<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROBOOST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styler.css">
</head>
<body>

    <!-- Navigacijski izbornik -->
    <?php include 'navbar.php'; ?>

    <!-- Sign In Modal -->
    <div class="modal fade" id="signInModal" tabindex="-1" aria-labelledby="signInModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="signInModalLabel">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            Dobrodošli, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                        <?php else: ?>
                            Prijavi se
                        <?php endif; ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <p>Već ste prijavljeni!</p>
                    <?php else: ?>
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Lozinka</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" id="password" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password">Prikaži</button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Prijavi se</button>
                        </form>
                        <div class="mt-3 text-center">
                            <span>Nemate račun? <a href="#" class="text-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal">Registrirajte se</a></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Registriraj se</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="register.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Ime</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label">Prezime</label>
                            <input type="text" class="form-control" id="surname" name="surname" required>
                        </div>
                        <div class="mb-3">
                            <label for="regEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="regEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="regPassword" class="form-label">Lozinka</label>
                            <input type="password" class="form-control" id="regPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registriraj se</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Košarica</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="cartItemsList">
                        <li>Vaša košarica je trenutno prazna.</li>
                    </ul>
                    <p id="totalPrice">Ukupna cijena: 0.00 €</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
                    <button type="button" class="btn btn-primary" id="checkoutButton">Nastavi na naplatu</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Your other content goes here -->

    
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
