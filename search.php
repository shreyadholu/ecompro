<?php
require 'db.php'; // Include database connection

$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
$searchResults = [];
$tables = ['topss', 'bags', 'bottomwear', 'dresses', 'footwear']; // Tables to search

if (!empty($searchQuery)) {
    $searchTerm = "%$searchQuery%";

    foreach ($tables as $table) {
        // SQL query to search in each table
        $sql = "SELECT id, name, price, image, '$table' AS category FROM $table WHERE name LIKE ?";
        
        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();

        // Fetch results into an array
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .product-card {
            text-align: center;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
        <div class="row g-4">
            <?php if (empty($searchResults)): ?>
                <p>No products found.</p>
            <?php else: ?>
                <?php foreach ($searchResults as $product): ?>
                    <div class="col-md-3">
                        <div class="product-card">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <h5><?php echo $product['name']; ?></h5>
                            <p class="text-primary fw-bold">₹<?php echo $product['price']; ?></p>
                            <small class="text-muted">Category: <?php echo ucfirst($product['category']); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
