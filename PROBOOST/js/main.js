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
        
        //filtering
        const veganFilterCheckbox = document.getElementById('veganFilter');
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');
        const productsContainer = document.querySelector('.products-container');

        function filterProducts() {
            if (!productsContainer) return;

            const veganOnly = veganFilterCheckbox?.checked || false;
            const maxPrice = parseFloat(priceRange?.value || Infinity);

            document.querySelectorAll('.product').forEach(product => {
                const price = parseFloat(product.dataset.price);
                const isVegan = product.dataset.vegan === '1';

                

                // Prikaz ili sakrivanje proizvoda na temelju filtera
                if (((veganOnly && isVegan) || !veganOnly) && price <= maxPrice) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }

                // Ponovno osiguranje stilova za slike
                const productImage = product.querySelector('img');
                if (productImage) {
                    productImage.style.width = '100%';
                    productImage.style.height = '200px';
                    productImage.style.objectFit = 'cover';
                }
            });
        }

        // Ažuriranje prikaza cijene i pokretanje filtriranja
        if (priceRange) {
            priceRange.addEventListener('input', function () {
                if (priceValue) {
                    priceValue.textContent = `0€ - ${priceRange.value}€`;
                }
                filterProducts();
            });
        }

        // Aktiviranje filtriranja na promjenu veganske opcije
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
    



        // Cart functionality
        // Function to update the cart modal
        function updateCartModal() {
            const cartItemsList = $('#cartItemsList');
            cartItemsList.empty();
            let totalPrice = 0;

            if (cartItems.length === 0) {
                cartItemsList.append('<li>Vaša košarica je trenutno prazna.</li>');
            } else {
                cartItems.forEach(item => {
                    cartItemsList.append(`<li>${item.name} - ${item.price.toFixed(2)} €</li>`);
                    totalPrice += item.price;
                });
            }

            $('#totalPrice').text(`Ukupna cijena: ${totalPrice.toFixed(2)} €`);
        }

        $(document).ready(function() {
            // Update the cart modal when it is shown
            $('#cartModal').on('show.bs.modal', function () {
                updateCartModal();
            });

            $('#checkoutButton').click(function() {
                // Send the cart items to the server for processing
                $.ajax({
                    url: 'process_order.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(cartItems),
                    success: function(response) {
                        const result = JSON.parse(response);
                        alert(result.message);
                        if (result.status === 'success') {
                            // Optionally clear the cart or redirect
                            cartItems.length = 0; // Clear the cart
                            $('#cartModal').modal('hide'); // Hide the modal
                        }
                    },
                    error: function() {
                        alert('Došlo je do greške prilikom obrade narudžbe.');
                    }
                });
            });
        });
            
        
        
   

        

        filterProducts();
        
    });
    