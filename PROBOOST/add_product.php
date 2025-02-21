<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Obrada slike
    if (isset($_FILES['image'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_upload_path = 'uploads/' . basename($image_name);

        // Provjera i premještanje slike u folder "uploads"
        if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
            echo "Slika uspješno dodana!";
        } else {
            echo "❗ Došlo je do pogreške pri prijenosu slike.";
            $image_upload_path = ''; // Ako dođe do greške u prijenosu slike, postavi na prazno
        }
    }

    $is_vegan = isset($_POST['is_vegan']) ? 1 : 0;

    // SQL upit za unos proizvoda u bazu
    $sql = "INSERT INTO products (name, description, price, image_url, is_vegan, category) VALUES (?, ?, ?, ?, ?, ?)"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsss", $name, $description, $price, $image_upload_path, $is_vegan, $category); 

    if ($stmt->execute()) {
        header("Location: admin.php"); // Preusmjeri na admin stranicu nakon uspješnog unosa
    } else {
        echo "Greška: " . $stmt->error; // Ako dođe do greške u izvršavanju upita
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj proizvod</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        label {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dodaj novi proizvod</h1>
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Naziv proizvoda:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Opis proizvoda:</label>
                <textarea id="description" name="description" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Cijena:</label>
                <input type="number" id="price" name="price" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="image">Slika proizvoda:</label>
                <input type="file" id="image" name="image" accept="image/*" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="is_vegan">Veganski proizvod:</label>
                <input type="checkbox" id="is_vegan" name="is_vegan">
            </div>

            <div class="form-group">
                <label for="category">Kategorija:</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="Proteini">Proteini</option>
                    <option value="Vitamini">Vitamini</option>
                    <option value="Snacks">Snacks</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Dodaj proizvod</button>
        </form>

        <a href="admin.php">Povratak na admin sučelje</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
