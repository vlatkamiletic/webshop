<?php 
session_start();
include 'db.php'; // Povezivanje s bazom koristeći MySQLi
include 'base.php';

$page_title = "Dobrodošli u naš webshop!";

// Dohvati sve proizvode iz baze koristeći MySQLi
$sql = "SELECT * FROM products";
$result = $conn->query($sql); // Koristi MySQLi query

// Provjera rezultata
if (!$result) {
    die("Greška u upitu: " . $conn->error);
}

// Dohvati sve proizvode kao asocijativni niz
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop - Proteini</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styler.css">
</head>
<body>
    
    <main>
        <!-- Display success or error message -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" id="success-message">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); // Clear the message after displaying it ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" id="error-message">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); // Clear the message after displaying it ?>
        <?php endif; ?>

        <!-- Filtri -->
        <div class="filters">
            <div class="filter-price">
                <label for="priceRange">Cijena:</label>
                <input type="range" id="priceRange" min="0" max="50" step="1" value="50">
                <span id="priceValue">0€ - 50€</span>
            </div>
            <div class="filter-vegan">
                <label for="veganFilter"></label>
                <input type="checkbox" id="veganFilter">
                <span>Veganski proizvodi</span>
            </div>
        </div>

        <!-- Proizvodi -->
        <div class="products-container">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product" data-id="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>" data-vegan="<?= $product['is_vegan'] ?>">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <h4 class="price"><?= $product['price'] ?>€</h4>
                        <div class="quantity-selector">
                            <label for="quantity_<?= $product['id'] ?>">Količina:</label>
                            <div class="input-group">
                                <input type="number" class="form-control quantity-input" id="quantity_<?= $product['id'] ?>" min="1" value="1" step="1" max="10">
                            </div>
                        </div>
                        <button class="add-to-cart" data-id="<?= $product['id'] ?>">Dodaj u košaricu</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nema proizvoda za prikaz.</p>
            <?php endif; ?>
        </div>
    </main>


    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>


    <script>
    // Hide success message after 3 seconds
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000); // 3000 milliseconds = 3 seconds

    // Hide error message after 3 seconds (optional)
    setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 3000); // 3000 milliseconds = 3 seconds
    </script>

</body>
</html>