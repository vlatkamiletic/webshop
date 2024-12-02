<?php
session_start();
include 'db.php';
include 'base.html';

// Dohvati sve proizvode iz baze
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Provjeri da li je SQL upit uspio
if (!$result) {
    die("Greška u upitu: " . $conn->error);
}

$page_title = "Dobrodošli u naš webshop!";
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
    <header>
        <h1>Najbolja ponuda proteina, vitamina i snackova!</h1>
    </header>
    <main>

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
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>

                    <div class="product" data-id="<?= $row['id'] ?>" data-price="<?= $row['price'] ?>" data-vegan="<?= $row['is_vegan'] ?>">
                        <img src="<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" class="product-img">
                        <h3><?= $row['name'] ?></h3>
                        <p><?= $row['description'] ?></p>
                        <h4 class="price"><?= $row['price'] ?>€</h4>

                        <!-- Spinner za količinu -->
                        <div class="quantity-selector">
                            <label for="quantity_<?= $row['id'] ?>">Količina:</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary decrease" id="decrease_<?= $row['id'] ?>">-</button>
                                <input type="number" class="form-control quantity-input" id="quantity_<?= $row['id'] ?>" min="1" value="1" step="1" max="100">
                                <button type="button" class="btn btn-outline-secondary increase" id="increase_<?= $row['id'] ?>">+</button>
                            </div>
                        </div>
                        <button class="add-to-cart" data-id="<?= $row['id'] ?>">Dodaj u košaricu</button>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p>Nema proizvoda za prikaz.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modali -->
    <?php include 'includes/modals.html'; ?>

    <script src="js/main.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script> 
</body>
</html>

<?php
// Zatvori konekciju
$conn->close();
?>
