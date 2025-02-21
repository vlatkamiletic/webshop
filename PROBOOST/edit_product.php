<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Obrada slike
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Obrada nove slike
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_upload_path = 'uploads/' . basename($image_name);
    
        // Premještanje slike
        if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
            echo "Slika uspješno dodana!";
        } else {
            echo "❗ Došlo je do pogreške pri prijenosu slike.";
            $image_upload_path = $product['image_url']; // Ako nije nova slika, koristi postojeću
        }
    } else {
        // Ako slika nije promijenjena, koristi trenutnu sliku iz baze
        $image_upload_path = $product['image_url']; // Očuvaj staru sliku
    }
    

    $is_vegan = isset($_POST['is_vegan']) ? 1 : 0;

    // SQL upit za ažuriranje proizvoda
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, is_vegan = ?, category = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsssi", $name, $description, $price, $image_upload_path, $is_vegan, $category, $id);

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
    <title>Uredi Proizvod</title>
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
        <h1>Uredi Proizvod</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Naziv proizvoda:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Opis proizvoda:</label>
                <textarea id="description" name="description" class="form-control" required><?php echo $product['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Cijena proizvoda:</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo $product['price']; ?>" required>
            </div>

            <div class="form-group">
                <label>Slika proizvoda:</label>
                <?php if (!empty($product['image_url'])): ?>
                    <p>Trenutna slika: <?php echo basename($product['image_url']); ?></p>
                <?php else: ?>
                    <p>Trenutna slika nije postavljena.</p>
                <?php endif; ?>

                <label for="image">Odaberite novu sliku (ako želite promijeniti):</label>
                <input type="file" id="image" name="image" accept="image/*" class="form-control">
            </div>

            <div class="form-group">
                <label for="is_vegan">Veganski proizvod:</label>
                <input type="checkbox" id="is_vegan" name="is_vegan" <?php echo $product['is_vegan'] ? 'checked' : ''; ?>>
            </div>

            <div class="form-group">
                <label for="category">Kategorija:</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="Proteini" <?php echo $product['category'] == 'Proteini' ? 'selected' : ''; ?>>Proteini</option>
                    <option value="Vitamini" <?php echo $product['category'] == 'Vitamini' ? 'selected' : ''; ?>>Vitamini</option>
                    <option value="Snacks" <?php echo $product['category'] == 'Snacks' ? 'selected' : ''; ?>>Snacks</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Spremi Promjene</button>
        </form>

        <a href="admin.php">Povratak na admin sučelje</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
