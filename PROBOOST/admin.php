<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Spajanje na bazu

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        .btn {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            display: inline-block;
            margin: 5px;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        table td img {
            width: 50px;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        table th:nth-child(4), table td:nth-child(4) {
            width: 75px; /* Širina za stupac Cijena */
        }

    </style>
</head>
<body>
    <h1>Upravljanje proizvodima</h1>
    <a href="add_product.php" class="btn btn-success">Dodaj novi proizvod</a>
    
    <!-- Gumb za povratak na početnu stranicu -->
    <div class="container mt-3">
        <a href="index.php" class="btn btn-primary">Povratak na početnu stranicu</a>
    </div>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Naziv</th>
            <th>Opis</th>
            <th>Cijena</th>
            <th>Slika</th>
            <th>Kategorija</th>
            <th>Akcije</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['price']; ?> €</td>
                <td><img src="<?php echo $row['image_url']; ?>" alt="Product Image"></td>
                <td><?php echo $row['category']; ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Uredi</a>
                    <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Jeste li sigurni da želite obrisati ovaj proizvod?');">Obriši</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
