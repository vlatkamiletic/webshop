document.addEventListener('DOMContentLoaded', function () {
    const veganFilterCheckbox = document.getElementById('veganFilter');
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    const productsContainer = document.querySelector('.products-container');
    const cartItemsList = document.getElementById('cartItemsList');
    const checkoutButton = document.getElementById('checkoutButton');
    const totalPriceElement = document.getElementById('totalPrice');
    
    // Cart
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Filter products
    function filterProducts() {
        const veganOnly = veganFilterCheckbox.checked;
        const maxPrice = priceRange.value;

        const products = document.querySelectorAll('.product');
        products.forEach(product => {
            const price = parseFloat(product.dataset.price);
            const isVegan = product.dataset.vegan === '1';

            if ((veganOnly && isVegan || !veganOnly) && price <= maxPrice) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });

        // Trigger reflow for layout adjustment
        productsContainer.style.display = 'none';
        productsContainer.offsetHeight;
        productsContainer.style.display = 'flex';
    }

    // Price Range Event Listener
    priceRange.addEventListener('input', function () {
        priceValue.textContent = `${priceRange.value}€`;
        filterProducts();
    });

    // Vegan Filter Event Listener
    veganFilterCheckbox.addEventListener('change', filterProducts);

    // Handle adding items to cart
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            const quantity = document.getElementById(`quantity_${productId}`).value;
            addToCart(productId, quantity);
        });
    });

    // Add product to cart and save to localStorage
    function addToCart(productId, quantity) {
        const productPrice = parseFloat(document.querySelector(`.product[data-id="${productId}"]`).dataset.price);
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        const existingProductIndex = cart.findIndex(item => item.id === productId);
        if (existingProductIndex >= 0) {
            cart[existingProductIndex].quantity += parseInt(quantity);
        } else {
            cart.push({ id: productId, quantity: parseInt(quantity), price: productPrice });
        }

        // Save updated cart to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        alert('Proizvod je dodan u košaricu!');
        updateCartModal(); // Update cart modal after adding product
    }

    // Update Cart Modal UI
    function updateCartModal() {
        if (cart.length === 0) {
            cartItemsList.innerHTML = '<li>Tvoja košarica je prazna.</li>';
            totalPriceElement.textContent = 'Ukupna cijena: 0.00€';
            checkoutButton.disabled = true;
        } else {
            cartItemsList.innerHTML = '';
            let totalPrice = 0;
            
            cart.forEach((item, index) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    Proizvod ${item.id} - ${item.quantity} x ${item.price.toFixed(2)}€
                    <button class="remove-from-cart" data-index="${index}">Ukloni</button>
                `;
                cartItemsList.appendChild(li);
                totalPrice += item.price * item.quantity;
            });

            totalPriceElement.textContent = `Ukupna cijena: ${totalPrice.toFixed(2)}€`;
            checkoutButton.disabled = false;

            // Remove product from cart
            cartItemsList.querySelectorAll('.remove-from-cart').forEach(button => {
                button.addEventListener('click', (e) => {
                    const index = e.target.getAttribute('data-index');
                    cart.splice(index, 1);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartModal(); // Update modal after removing item
                });
            });
        }
    }

    // Checkout Button Click
    checkoutButton.addEventListener('click', () => {
        if (cart.length > 0) {
            alert('Plaćanje trenutno nije implementirano. Hvala na kupovini!');
        } else {
            alert('Košarica je prazna.');
        }
    });

    // Password visibility toggle
    document.querySelectorAll('.toggle-password').forEach(toggleButton => {
        toggleButton.addEventListener('click', function() {
            const passwordField = document.getElementById(this.dataset.target);
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
        });
    });

    // Initial setup and filters
    updateCartModal();
    filterProducts();
});
