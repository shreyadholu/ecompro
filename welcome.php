<?php
$conn = mysqli_connect("localhost", "root", "", "website");

// Check if a search term is provided
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Modify the query to include a search condition
$query = "SELECT * FROM products";
if (!empty($search)) {
    $query .= " WHERE name LIKE '%$search%' OR price LIKE '%$search%'";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .product {
            border: 1px solid #ccc;
            width: 250px;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .product h2 {
            margin: 10px 0;
            font-size: 20px;
            color: #555;
        }
        .product p {
            margin: 5px 0 10px;
            color: #777;
        }
        .product button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .product button:hover {
            background-color: #218838;
        }
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-container button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Welcome to the E-commerce Site</h1>
    <h2>Available Products</h2>

    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search for products..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

     <!-- Link to Cart Page -->
     <div class="cart-link">
        <a href="cart.php">Go to Cart</a>
    </div>

    <div class="products-container">
    <?php
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            echo "<div class='product'>";
            echo "<form method='POST' action='cart.php'>";
            echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
            echo "<h2>" . $row['name'] . "</h2>";
            echo "<p>Price: ₹" . $row['price'] . "</p>";
            echo "<input type='hidden' name='name' value='" . htmlspecialchars($row['name']) . "'>";
            echo "<input type='hidden' name='price' value='" . htmlspecialchars($row['price']) . "'>";
            echo "<input type='hidden' name='image' value='" . htmlspecialchars($row['image']) . "'>";
            echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
    </div>              
</body>
</html>
