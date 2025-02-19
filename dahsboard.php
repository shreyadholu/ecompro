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

        .slideshow-container {
            position: relative;
            width: 100%;
            margin: 10px 0;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .slides {
            display: none;
        }
        .slides img {
            width: 100%;
            border-radius: 0; /* Remove border radius for full-width */
        }
        .slideshow-container .prev, .slideshow-container .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 50%;
            z-index: 10;
        }
        .slideshow-container .prev {
            left: 10px;
        }
        .slideshow-container .next {
            right: 10px;
        }
        .slideshow-container .prev:hover, .slideshow-container .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
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


    <div class="slideshow-container">
        <div class="slides">
            <img src="images/slide1.jpg" alt="Slide 1">
        </div>
        <div class="slides">
            <img src="images/slide2.jpg" alt="Slide 2">
        </div>
        <div class="slides">
            <img src="images/slide3.jpg" alt="Slide 3">
        </div>
        <div class="slides">
            <img src="images/slide4.jpg" alt="Slide 4">
        </div>
        <div class="slides">
            <img src="images/slide5.jpg" alt="Slide 5">
        </div>
        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="next" onclick="changeSlide(1)">&#10095;</button>
    </div>

    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let slides = document.querySelectorAll('.slides');
            slides.forEach(slide => slide.style.display = 'none');
            slideIndex++;
            if (slideIndex > slides.length) slideIndex = 1;
            slides[slideIndex - 1].style.display = 'block';
            setTimeout(showSlides, 3000); // Change image every 3 seconds
        }

        function changeSlide(n) {
            let slides = document.querySelectorAll('.slides');
            slides.forEach(slide => slide.style.display = 'none');
            slideIndex += n;
            if (slideIndex > slides.length) slideIndex = 1;
            if (slideIndex < 1) slideIndex = slides.length;
            slides[slideIndex - 1].style.display = 'block';
        }
    </script>
</body>
</html>