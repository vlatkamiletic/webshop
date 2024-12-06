<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styler.css">
</head>
<body>

<?php

include 'db.php'; 
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
    // Hide success or error messages 
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) successMessage.style.display = 'none';

        var errorMessage = document.getElementById('error-message');
        if (errorMessage) errorMessage.style.display = 'none';
    }, 3000);
</script>


<script>
        $(document).ready(function() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Dodavanje proizvoda u košaricu
            $('.add-to-cart').click(function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productPrice = parseFloat($(this).data('price'));
                const quantity = parseInt($(`#quantity_${productId}`).val());

                // Provjeri ako proizvod već postoji u košarici
                const cartItem = cart.find(item => item.id === productId);
                if (cartItem) {
                    cartItem.quantity += quantity;  // Ako postoji, poveća količinu
                } else {
                    cart.push({ id: productId, name: productName, price: productPrice, quantity: quantity });
                }
                
                // Spremi košaricu u localStorage
                localStorage.setItem('cart', JSON.stringify(cart));
                

                updateCart();
            });

            // Ažuriranje košarice (prikaz u modalu)
            function updateCart() {
                const cartItemsList = $('#cartItemsList');
                const totalPriceElement = $('#totalPrice');
                cartItemsList.empty();
                let totalPrice = 0;

                if (cart.length === 0) {
                    cartItemsList.append('<li>Vaša košarica je trenutno prazna.</li>');
                } else {
                    cart.forEach(item => {
                        const itemTotal = item.price * item.quantity;
                        totalPrice += itemTotal;

                        // Dodavanje gumba za promjenu količine
                        cartItemsList.append(`
                            <li>
                                ${item.name} 
                                - <button class="decrease-quantity" data-id="${item.id}">-</button>
                                <span id="quantity_${item.id}">${item.quantity}</span>
                                <button class="increase-quantity" data-id="${item.id}">+</button> 
                                x ${item.price}€ = ${itemTotal.toFixed(2)}€
                            </li>
                        `);
                    });
                }

                totalPriceElement.text(`Ukupna cijena: ${totalPrice.toFixed(2)} €`);
            }

            // Povećaj količinu proizvoda
            $(document).on('click', '.increase-quantity', function() {
                const productId = $(this).data('id');
                const cartItem = cart.find(item => item.id === productId);
                if (cartItem) {
                    cartItem.quantity += 1;  // Povećaj količinu za 1
                    $('#quantity_' + productId).text(cartItem.quantity); // Ažuriraj količinu na ekranu
                    updateCart();  // Ažuriraj cijenu
                    localStorage.setItem('cart', JSON.stringify(cart));  // Spremi u localStorage
                }
            });

            // Smanji količinu proizvoda
            $(document).on('click', '.decrease-quantity', function () {
                const productId = $(this).data('id'); // Dohvati ID proizvoda
                const cartItemIndex = cart.findIndex(item => item.id === productId); // Nađi indeks proizvoda u košarici

                if (cartItemIndex !== -1) { // Ako proizvod postoji u košarici
                    const cartItem = cart[cartItemIndex];
                    
                    if (cartItem.quantity > 1) {
                        cartItem.quantity -= 1; // Smanji količinu za 1
                    } else {
                        cart.splice(cartItemIndex, 1); // Ukloni proizvod iz košarice
                    }

                    updateCart();
                    localStorage.setItem('cart', JSON.stringify(cart)); // Spremi ažuriranu košaricu u localStorage
                }
            });

            updateCart();
        });
        </script>

</body>
</html>
