<?php
$products = [
    ["name" => "Classic Blue Jeans", "price" => "₹1199", "image" => "images/denimjeans.jpg"],
    ["name" => "Casual Joggers", "price" => "₹999", "image" => "images/joggers.jpeg"],
    ["name" => "BLack Trousers", "price" => "₹1399", "image" => "images/blacktrousers.jpeg"],
    ["name" => "Black Skirt", "price" => "₹899", "image" => "images/blackskirt.jpeg"],
    ["name" => "Beige Trousers", "price" => "₹1299", "image" => "images/beigetrousers.jpg"],
    ["name" => "Denim Shorts", "price" => "₹1099", "image" => "images/denimshorts.jpeg"],
    ["name" => "Black Leggings", "price" => "₹799", "image" => "images/blackleggings.jpeg"],
    ["name" => "Wide Leg Jeans", "price" => "₹1499", "image" => "images/widelegjeans.jpeg"]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bottomwear</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: white;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar .logo {
            font-family: fantasy, cursive;
            font-size: 30px;
            font-weight: bold;
            color: #333;
        }
        .navbar .nav-items {
            display: flex;
            align-items: center;
        }
        .navbar .nav-items button {
            margin: 0 10px;
            padding: 8px 16px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #333;
            transition: color 0.3s ease;
        }
        .navbar .nav-items button:hover {
            color: #555;
        }
        .navbar .nav-items .search-bar {
            margin: 0 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .catalog-container { padding: 20px; }
        .product-card {
            text-align: center;
            border: 1px solid #ddd;
            padding: 15px;
            order-radius: 10px;
            overflow: hidden;
            height: 100%; /* Ensures all cards have equal height */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-card img {
        width: 100%;
        height: 200px; /* Adjust height to fit properly */
        object-fit: contain; /* Ensures image covers the space properly */
        border-radius: 10px;
    }   

    .add-to-cart {
    margin-top: 10px;
    background-color: #c9c5b1; 
    color: white;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    border: none;
    border-radius: 8px;
    padding: 10px;
    transition: background-color 0.3s ease, transform 0.2s;
}

.add-to-cart:hover {
    background-color: #787569; 
    transform: scale(1.05);
}

.add-to-cart i {
    font-size: 18px;
}

    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">Closetly</div>
        <div class="nav-items">
            <button>Home</button>
            <button>Shop</button>
            <button>Contact</button>
            <input type="text" class="search-bar" placeholder="Search...">
        </div>
    </div>
    <div class="container catalog-container">
        <h2 class="text-center mb-4">Bottomwear</h2>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-3">
                    <div class="product-card">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <h5 class="mt-2"><?php echo $product['name']; ?></h5>
                        <p class="text-primary fw-bold"><?php echo $product['price']; ?></p>
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
