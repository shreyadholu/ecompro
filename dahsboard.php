<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Closetly</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #d9c2ba; /* Light beige */
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

        .catalog-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            padding: 20px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
        }
        .catalog-box {
            width: 200px;
            height: 200px;
            margin: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none; /* Remove underline */
        }
        .catalog-box:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .catalog-box::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 8px;
        }
        .catalog-box span {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">Closetly</div>
        <div class="nav-items">
            <input type="text" class="search-bar" placeholder="Search...">
            <button>Account</button>
            <button>Cart</button>
        </div>
    </div>


    <div class="catalog-container">
        <a href="tops.html" target="_blank" class="catalog-box" style="background-image: url('images/tops.jpg');">
            <span>Tops</span>
        </a>
        <a href="bottomwear.html" target="_blank" class="catalog-box" style="background-image: url('images/bottomwear.jpg');">
            <span>Bottomwear</span>
        </a>
        <a href="dresses.html" target="_blank" class="catalog-box" style="background-image: url('images/dresses.jpg');">
            <span>Dresses</span>
        </a>
        <a href="bags.html" target="_blank" class="catalog-box" style="background-image: url('images/bags.jpg');">
            <span>Bags and Clutches</span>
        </a>
        <a href="footwear.html" target="_blank" class="catalog-box" style="background-image: url('images/footwear.jpg');">
            <span>Footwear</span>
        </a>
    </div>

</body>
</html>
