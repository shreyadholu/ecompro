<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$database = "capsule_wardrobe";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$uploadDir = "uploads/";
$categories = ["topss", "bags", "bottomwear", "dresses", "footwear"];

foreach ($categories as $category) {
    $categoryDir = $uploadDir . $category . "/"; // Example: uploads/tops/

    if (!is_dir($categoryDir)) continue; // Skip if directory doesn't exist

    // Get all image files inside the category folder
    $files = glob($categoryDir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

    foreach ($files as $file) {
        $fileName = basename($file);
        $imagePath = $categoryDir . $fileName; // Example: uploads/tops/image1.jpg

        // Check if image is already stored in the correct category table
        $checkQuery = "SELECT * FROM $category WHERE image = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $imagePath);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) { // Insert only if not already stored
            $insertQuery = "INSERT INTO $category (name, price, image) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            
            // Generate name from file name (Remove underscores, capitalize)
            $name = ucfirst(str_replace("_", " ", pathinfo($fileName, PATHINFO_FILENAME)));
            $price = rand(500, 2000); // Random price (Modify as needed)
            
            $stmt->bind_param("sds", $name, $price, $imagePath);
            
            if ($stmt->execute()) {
                echo "✅ Inserted: $imagePath into $category table<br>";
            } else {
                echo "❌ Error inserting $imagePath: " . $stmt->error . "<br>";
            }
        }
    }
}

echo "🎉 All images uploaded successfully!";
$conn->close();
?>
