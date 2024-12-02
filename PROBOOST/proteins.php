<?php
// Uključi db.php za povezivanje s bazom
include 'db.php';
include 'base.html';

// Dohvati proizvode za proteine
$sql = "SELECT * FROM products WHERE category = 'Proteini'"; // Dodano filtriranje prema kategoriji
$result = $conn->query($sql);

// Provjeri da li je SQL upit uspio
if (!$result) {
    die("Greška u upitu: " . $conn->error);
}

// Definiraj varijablu za naziv stranice
$page_title = "Proteini - Webshop";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop - Proteini</title>
    <link rel="stylesheet" href="styler.css">
</head>
<body>
    <header>
        <h1>Najbolja ponuda proteina!</h1>
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
                    <div class="product" data-price="<?= $row['price'] ?>" data-vegan="<?= $row['is_vegan'] ?>">
                        <img src="<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" class="product-img">
                        <h3><?= $row['name'] ?></h3>
                        <p><?= $row['description'] ?></p>
                        <h4><?= $row['price'] ?>€</h4>
                        <button class="add-to-cart" data-id="<?= $row['id'] ?>">Dodaj u košaricu</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nema proizvoda za prikaz.</p>
            <?php endif; ?>
        </div>
    </main>

    <script src="js/main.js"></script> 
</body>
</html>

<?php
// Zatvori konekciju
$conn->close();
?>
