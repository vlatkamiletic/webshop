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
            myModal.show();
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
            document.body.style.overflow = ''; 
            
            // Ukloni padding sa desne strane nakon zatvaranja modala
            document.body.style.paddingRight = '';
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
                        signInModal.hide();
                        window.location.reload(); 
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
                        registerModal.hide(); 
                        updateNavbar(); 
                    } else {
                        alert(data.message);
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
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error("Greška pri odjavi:", error);
                        alert("Došlo je do pogreške prilikom odjave.");
                    });
            }
        });

        filterProducts();
        
    });
    