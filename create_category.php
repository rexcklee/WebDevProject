<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('authenticate.php');
require('connect.php');
require('validation.php');

function file_upload_path($original_filename, $upload_subfolder_name = 'UploadImage') {
    $current_folder = dirname(__FILE__);
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    return join(DIRECTORY_SEPARATOR, $path_segments);
}

function file_is_an_image($temporary_path, $new_path) {
    $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];

    $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type        = (getimagesize($temporary_path) != false) ? getimagesize($temporary_path)['mime'] : null;

    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

    return $file_extension_is_valid && $mime_type_is_valid;
}

function resize_an_image($image_path) {
    // Set the new image size
    $new_width  = 1500;
    $new_height = 1000;

    // Load the image
    $thumb = imagecreatetruecolor($new_width, $new_height);
    $source = imagecreatefromjpeg($image_path);
    // Get the source image size
    list($width, $height) = getimagesize($image_path);

    // Resize the image
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    imagejpeg($thumb, $image_path);

    return $image_path;
}

// Insert new row if input is valid, else direct to error message page.
if ($_POST && input_is_valid())
{
    $image_upload_detected = isset($_FILES['cat_image']) && ($_FILES['cat_image']['error'] === 0);
    $food_category_image = "";

    if ($image_upload_detected)
    {
        $image_filename       = $_FILES['cat_image']['name'];
        $temporary_image_path = $_FILES['cat_image']['tmp_name'];
        $new_image_path       = file_upload_path($image_filename);

        if (file_is_an_image($temporary_image_path, $new_image_path))
        {
            $temporary_image_path = resize_an_image($temporary_image_path);
            
            $food_category_image = $image_filename;
            move_uploaded_file($temporary_image_path, $new_image_path);
        }
        else{
            die("Upload error! The file is not an image.");
        }
    }
    
    //  Sanitize user input to escape HTML entities and filter out dangerous characters.
    $food_category_name = filter_input(INPUT_POST, 'food_category_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $food_category_description = filter_input(INPUT_POST, 'food_category_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    //  Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO foodcategories (food_category_name, food_category_description, food_category_image) VALUES (:food_category_name, :food_category_description, :food_category_image)";
    $statement = $db->prepare($query);
    
    //  Bind values to the parameters
    $statement->bindValue(':food_category_name', $food_category_name);
    $statement->bindValue(':food_category_description', $food_category_description);
    $statement->bindValue(':food_category_image', $food_category_image);
    
    //  Execute the INSERT.
    if($statement->execute())
    {
        header('Location: admin.php');;
    }
}
// Handle input invalid error
else if ($_POST && !input_is_valid()) 
{
    header('Location: error.php');
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gabarito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Oishii Japanese Restaurant</title>
</head>
<body>
    
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Oishii Japanese Restaurant</a></h1>
        </div> 

        <ul id="admin_menu">
            <li><a href="admin.php">Food Items</a></li>
            <li><a href="create_dish.php">New Dish</a></li>
            <li><a href="food_categories.php">Categories</a></li>
            <li><a href="create_category.php" class="active">New Category</a></li>
        </ul> 

        <div id="main_content">
            <form action="create_category.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>New Food Category</legend>
                    <p> 
                        <label for="food_category_name">Food Category Name</label>
                        <input class="form-control" name="food_category_name" id="food_category_name" />
                    </p>
                    <p>
                        <label for="food_category_description">Food Category Description</label>
                        <textarea class="form-control" name="food_category_description" id="food_category_description"></textarea>
                    </p>
                    <p>
                        <label for="image">Image Filename</label>
                        <input class="form-control" type="file" name="cat_image" id="cat_image">
                    </p>
                    <p>                       
                        <button type="submit" class="btn btn-primary" name="command">Create</button>
                    </p>
                </fieldset>
            </form>
        </div>

        <div id="footer">
            Copyright 2023 - Rights reserved by Rex
        </div> 
    </div> 
</body>
</html>