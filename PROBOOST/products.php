<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styler.css">
</head>
<body>

<?php
// Include the database connection and base files
include 'db.php'; // Ensure this path is correct
include 'base.php';

// Get the category from the URL
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch products based on the selected category
if (!empty($category)) {
    $sql = "SELECT * FROM products WHERE category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch products
    $products = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $products = []; // No products if no category is selected
}

$conn->close();
?>

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
                    <button class="add-to-cart" data-id="<?= $product['id'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>" data-price="<?= $product['price'] ?>">Dodaj u košaricu</button>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nema proizvoda za prikaz u ovoj kategoriji.</p>
        <?php endif; ?>
    </div>
</main>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/main.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

<script>
    // Hide success or error messages after 3 seconds
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) successMessage.style.display = 'none';

        var errorMessage = document.getElementById('error-message');
        if (errorMessage) errorMessage.style.display = 'none';
    }, 3000);
</script>

</body>
</html>
