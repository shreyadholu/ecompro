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

        /* Popup Dialog */
        .cart-dialog {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1000;
            text-align: center;
        }
        .cart-dialog button {
            background-color: #787569;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        .cart-dialog button:hover {
            background-color: #5d5a4f;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">Closetly</div>
    <div class="nav-items">
        <button onclick="window.location.href='homepage.php'">Home</button>
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
                    <form onsubmit="addToCart(event, this)">
                        <input type="hidden" name="id" value="<?php echo $tops['id']; ?>">
                        <input type="hidden" name="name" value="<?php echo $tops['name']; ?>">
                        <input type="hidden" name="price" value="<?php echo $tops['price']; ?>">
                        <input type="hidden" name="image" value="<?php echo $tops['image']; ?>">
                        <button type="submit" class="add-to-cart">
                            <i class="bi bi-cart"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Dialog Box -->
<div id="cartDialog" class="cart-dialog">
    <p>Item added to the cart successfully!</p>
    <button onclick="closeDialog()">OK</button>
</div>

<script>
    function addToCart(event, form) {
        event.preventDefault(); // Prevent form from reloading the page
        let formData = new FormData(form);

        fetch('cart_database.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('cartDialog').style.display = 'block'; // Show popup
        })
        .catch(error => console.error('Error:', error));
    }

    function closeDialog() {
        document.getElementById('cartDialog').style.display = 'none'; // Hide popup
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
