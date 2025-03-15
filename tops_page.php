<?php
require 'db.php'; // Include database connection

$sql = "SELECT id, name, price, image FROM topss";
$result = $conn->query($sql);
$topss = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $topss[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog - Tops</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: white; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: white; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .navbar .logo { font-family: fantasy, cursive; font-size: 30px; font-weight: bold; color: #333; }
        .navbar .nav-items { display: flex; align-items: center; }
        .navbar .nav-items button { margin: 0 10px; padding: 8px 16px; background: none; border: none; cursor: pointer; font-size: 16px; color: #333; transition: color 0.3s ease; }
        .navbar .nav-items button:hover { color: #555; }
        .catalog-container { padding: 20px; }
        .product-card { text-align: center; border: 1px solid #ddd; padding: 15px; border-radius: 10px; overflow: hidden; height: 100%; display: flex; flex-direction: column; justify-content: space-between; }
        .product-card img { width: 100%; height: 200px; object-fit: contain; border-radius: 10px; }
        .add-to-cart { margin-top: 10px; background-color: #c9c5b1; color: white; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 5px; border: none; border-radius: 8px; padding: 10px; transition: background-color 0.3s ease, transform 0.2s; }
        .add-to-cart:hover { background-color: #787569; transform: scale(1.05); }
        .add-to-cart i { font-size: 18px; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Closetly</div>
        <div class="nav-items">
            <button>Home</button>
            <button>Shop</button>
            <button>Contact</button>
            <form action="search.php" method="GET" class="d-flex">
                <input class="form-control" type="search" name="query" placeholder="Search..." required>
            </form>
        </div>
    </div>
    <div class="container catalog-container">
        <h2 class="text-center mb-4">Tops</h2>
        <div class="row g-4">
            <?php foreach ($topss as $tops): ?>
                <div class="col-md-3">
                    <div class="product-card">
                        <img src="<?php echo $tops['image']; ?>" alt="<?php echo $tops['name']; ?>">
                        <h5 class="mt-2"><?php echo $tops['name']; ?></h5>
                        <p class="text-primary fw-bold">₹<?php echo $tops['price']; ?></p>
                        <button class="add-to-cart">
                            <i class="bi bi-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
