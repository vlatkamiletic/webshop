document.addEventListener("DOMContentLoaded", function () {
        // Focus Management for Modals
        const signInModal = document.getElementById('signInModal');
        const registerModal = document.getElementById('registerModal');
    
        if (signInModal) {
            signInModal.addEventListener('shown.bs.modal', function () {
                const emailInput = document.getElementById('email');
                if (emailInput) emailInput.focus();
            });
        }
    
        if (registerModal) {
            registerModal.addEventListener('shown.bs.modal', function () {
                const nameInput = document.getElementById('firstName');
                if (nameInput) nameInput.focus();
            });
        }


        // Prikaz/skrivanje lozinke
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const passwordField = document.querySelector(this.getAttribute('data-target'));
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;
                this.textContent = type === 'password' ? 'Prikaži' : 'Sakrij';
            });
        });
        
    
        // Product Filtering
        const veganFilterCheckbox = document.getElementById('veganFilter');
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');
        const productsContainer = document.querySelector('.products-container');
    
        function filterProducts() {
            if (!productsContainer) return;
    
            const veganOnly = veganFilterCheckbox?.checked || false;
            const maxPrice = priceRange?.value || Infinity;
    
            document.querySelectorAll('.product').forEach(product => {
                const price = parseFloat(product.dataset.price);
                const isVegan = product.dataset.vegan === '1';
    
                product.style.display =
                    ((veganOnly && isVegan) || !veganOnly) && price <= maxPrice
                        ? 'block'
                        : 'none';
            });
    
            productsContainer.style.display = 'none';
            productsContainer.offsetHeight; // Force reflow
            productsContainer.style.display = 'flex';
        }
    
        if (priceRange) {
            priceRange.addEventListener('input', function () {
                if (priceValue) {
                    priceValue.textContent = `0€ - ${priceRange.value}€`;
                }
                filterProducts();
            });
        }
    
        if (veganFilterCheckbox) {
            veganFilterCheckbox.addEventListener('change', filterProducts);
        }



        document.getElementById('openCartButton').addEventListener('click', function() {
            // Kreiraj novi modal
            var myModal = new bootstrap.Modal(document.getElementById('cartModal'), {
                keyboard: false // Onemogući izlaz pomoću tipke ESC
            });
            myModal.show(); // Otvori modal
        });

        document.getElementById('cartModal').addEventListener('hidden.bs.modal', function () {
            // Ukloni backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            // Ukloni modal-open klasu s body-a
            document.body.classList.remove('modal-open');
            
            // Omogući ponovno skrolanje
            document.body.style.overflow = '';  // Vraća normalno skrolanje
            
            // Ukloni padding sa desne strane nakon zatvaranja modala
            document.body.style.paddingRight = '';  // Vraća padding na normalno
        });
        
        
            
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartItemsList = document.getElementById('cartItemsList');
        const totalPriceElement = document.getElementById('totalPrice');
        const checkoutButton = document.getElementById('checkoutButton');

        // Ažuriranje košarice u modal
        function updateCartModal() {
            if (cart.length === 0) {
                if (cartItemsList) {
                    cartItemsList.innerHTML = '<li>Košarica je prazna.</li>';
                }
                if (totalPriceElement) {
                    totalPriceElement.textContent = 'Ukupna cijena: 0.00€';
                }
                if (checkoutButton) {
                    checkoutButton.disabled = true;
                }
            } else {
                if (cartItemsList) {
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

                    if (totalPriceElement) {
                        totalPriceElement.textContent = `Ukupna cijena: ${totalPrice.toFixed(2)}€`;
                    }
                    if (checkoutButton) {
                        checkoutButton.disabled = false;
                    }
                }
            }
        }

        // Dodavanje proizvoda u košaricu
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-id');
                const quantityInput = document.getElementById(`quantity_${productId}`);
                const quantity = parseInt(quantityInput.value);
        
                if (quantity && quantity >= 1) {
                    addToCart(productId, quantity); // Pozivamo funkciju za dodavanje u košaricu
                }
            });
        });
        
        // Funkcija za dodavanje proizvoda u košaricu
        function addToCart(productId, quantity) {
            const productElement = document.querySelector(`.product[data-id="${productId}"]`);
            if (!productElement) return;
        
            const productPrice = parseFloat(productElement.dataset.price); // Uzima cijenu proizvoda
            const existingProductIndex = cart.findIndex(item => item.id === productId); // Provjerava postoji li proizvod u košarici
        
            // Ako proizvod već postoji u košarici, povećava količinu
            if (existingProductIndex >= 0) {
                cart[existingProductIndex].quantity += quantity;
            } else {
                // Ako proizvod ne postoji, dodaje ga u košaricu
                cart.push({ id: productId, quantity, price: productPrice });
            }
        
            // Pohranjuje ažuriranu košaricu u localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
        
            // Ažurira prikaz košarice u modalu
            updateCartModal();
        }
        

        // Event listener za minus i plus dugmadi za količinu
        document.querySelectorAll('.decrease').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.id.replace('decrease_', '');
                const quantityInput = document.getElementById(`quantity_${productId}`);
                let quantity = parseInt(quantityInput.value);
                
                if (quantity > 1) {
                    quantity--;
                    quantityInput.value = quantity;
                }
            });
        });

        document.querySelectorAll('.increase').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.id.replace('increase_', '');
                const quantityInput = document.getElementById(`quantity_${productId}`);
                let quantity = parseInt(quantityInput.value);

                if (quantity < 10) {
                    quantity++;
                    quantityInput.value = quantity;
                }
            });
        });

        // Inicijalizacija košarice prilikom učitavanja stranice
        updateCartModal();

        // Event listener za uklanjanje proizvoda iz košarice
        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', function () {
                const index = parseInt(this.getAttribute('data-index'));
                cart.splice(index, 1); // Ukloni proizvod iz košarice
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartModal(); // Ažuriraj modal nakon uklanjanja proizvoda
            });
        });

    
        // Authentication: Login and Register
        const navbarContainer = document.querySelector(".navbar");

        async function updateNavbar() {
            try {
                const response = await fetch("navbar.php");
                const html = await response.text();
                if (navbarContainer) {
                    navbarContainer.innerHTML = html;
                }
            } catch (error) {
                console.error("Greška pri ažuriranju navbar-a:", error);
                alert("Došlo je do pogreške prilikom ažuriranja navigacije.");
            }
        }

        const signInForm = document.querySelector("#signInModal form");
        if (signInForm) {
            signInForm.addEventListener("submit", async function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch("login.php", {
                        method: "POST",
                        body: formData,
                    });
                    const data = await response.text();
                    if (data === "success") {
                        const signInModal = new bootstrap.Modal(document.getElementById('signInModal'));
                        signInModal.hide(); // Zatvori modal
                        window.location.reload(); // Osvježi stranicu nakon prijave
                    } else {
                        alert(data);
                    }
                } catch (error) {
                    console.error("Greška prilikom prijave:", error);
                    alert("Došlo je do pogreške. Pokušajte ponovo.");
                }
            });
        }

        const registerForm = document.querySelector("#registerModal form");
        if (registerForm) {
            registerForm.addEventListener("submit", async function (e) {
                e.preventDefault(); // Spriječi osvježavanje stranice
                const formData = new FormData(this);

                try {
                    const response = await fetch("register.php", {
                        method: "POST",
                        body: formData,
                    });
                    const data = await response.json();
                    if (data.success) {
                        alert(data.message);
                        const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                        registerModal.hide(); // Zatvori modal
                        updateNavbar(); // Ažuriraj navbar
                    } else {
                        alert(data.message); // Prikaži poruku o grešci
                    }
                } catch (error) {
                    console.error("Greška pri registraciji:", error);
                    alert("Došlo je do pogreške. Pokušajte ponovo.");
                }
            });
        }

        document.addEventListener("click", function (e) {
            if (e.target && e.target.id === "logoutButton") {
                fetch("logout.php", { method: "GET" })
                    .then(() => {
                        updateNavbar(); // Ažuriraj navigaciju nakon odjave
                        window.location.reload(); // Osvježi stranicu
                    })
                    .catch(error => {
                        console.error("Greška pri odjavi:", error);
                        alert("Došlo je do pogreške prilikom odjave.");
                    });
            }
        });
    
        
        
    
        // Initialize
        updateCartModal();
        filterProducts();
        
    });
    