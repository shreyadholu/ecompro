<?php
$categories = [
    "Tops" => ["image" => "images/white_tshirt.jpg", "link" => "tops_page.php"],
    "Bottomwear" => ["image" => "images/widelegjeans.jpeg", "link" => "bottomwear_page.php"],
    "Dresses" => ["image" => "images/slipdress.jpg", "link" => "dresses.php"],
    "Footwear" => ["image" => "images/blackheels.jpg", "link" => "footwear.php"],
    "Bags & Clutches" => ["image" => "images/blackclutch.jpg", "link" => "bags.php"]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Closetly - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
            padding: 15px 20px;
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
            margin-left: 15px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        /* Updated Categories Section */
        .categories-container {
            padding: 40px;
            text-align: center;
        }
        .categories-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            justify-content: center;
            padding: 20px;
        }
        .category-card {
            border-radius: 12px;
            overflow: hidden;
            text-align: center;
            background: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        .category-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }
        .category-card img {
            width: 100%;
            height: 250px;
            object-fit: contain;
            border-bottom: 4px solid #c9c5b1;
        }
        .category-name {
            font-size: 20px;
            font-weight: bold;
            padding: 15px;
            color: #333;
            background: #f7f7f7;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">Closetly</div>
    <div class="nav-items">
        <button onclick="window.location.href='index.php'">Home</button>
        <button onclick="window.location.href='shop.php'">Shop</button>
        <button onclick="window.location.href='contact.php'">Contact</button>
        <input type="text" class="search-bar" placeholder="Search...">
    </div>
</div>

<!-- Categories Section -->
<div class="categories-container">
    <h2 class="categories-title">Shop by Category</h2>
    <div class="categories">
        <?php foreach ($categories as $name => $details): ?>
            <div class="category-card" onclick="window.location.href='<?php echo $details['link']; ?>'">
                <img src="<?php echo $details['image']; ?>" alt="<?php echo $name; ?>">
                <div class="category-name"><?php echo $name; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
