<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('authenticate.php');
require('connect.php');
require('validation.php');

function file_path($original_filename, $upload_subfolder_name = 'UploadImage') {
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

// Handle UPDATE
if (isset($_POST['command']))
{
    // Handle category UPDATE
    if (($_POST['command']=='Update') && input_is_valid() && isset($_POST['food_category_id']) && isset($_POST['food_category_image']))
    {   
        $image_upload_detected = isset($_FILES['cat_image']) && ($_FILES['cat_image']['error'] === 0);
        $food_category_image = $_POST['food_category_image'];
        
        if ($_POST['delete_image'] == 'delete')
        {
            $food_category_image = $_POST['food_category_image'];
            $delete_image_path   = file_path($food_category_image);

            if (file_exists($delete_image_path))
            {
                if (unlink($delete_image_path)) 
                {
                    echo 'File deleted successfully.';
                    $food_category_image = "";
                } 
                else 
                {
                    echo 'Unable to delete the file.';
                }
            } 
            else 
            {
                echo 'File does not exist.';
            }
        }

        if ($image_upload_detected)
        {
            $image_filename       = $_FILES['cat_image']['name'];
            $temporary_image_path = $_FILES['cat_image']['tmp_name'];
            $new_image_path       = file_path($image_filename);
            
            if (file_is_an_image($temporary_image_path, $new_image_path))
            {
                $temporary_image_path = resize_an_image($temporary_image_path);

                $food_category_image = $image_filename;
                move_uploaded_file($temporary_image_path, $new_image_path);
            }
            else
            {
                die("Upload error! The file is not an image.");
            }
        }
        
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $food_category_name = filter_input(INPUT_POST, 'food_category_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $food_category_description = filter_input(INPUT_POST, 'food_category_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $food_category_id = filter_input(INPUT_POST, 'food_category_id', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE foodcategories SET food_category_name = :food_category_name, food_category_description = :food_category_description, food_category_image = :food_category_image WHERE food_category_id = :food_category_id";

        $statement = $db->prepare($query);

        //  Bind values to the parameters
        $statement->bindValue(':food_category_name', $food_category_name);
        $statement->bindValue(':food_category_description', $food_category_description);
        $statement->bindValue(':food_category_image', $food_category_image);
        $statement->bindValue(':food_category_id', $food_category_id, PDO::PARAM_INT);
        
        // Execute the INSERT.
        $statement->execute();
        
        // Direct to admin page after update.
        header("Location: food_categories.php");
        exit;
    }
    // Handle input invalid error
    else if (($_POST['command']=='Update') && !input_is_valid() && isset($_POST['food_category_id']))
    {      
        header('Location: error.php');
    }
}

// Retrieve category information to be EDIT, if id GET parameter is in URL.
if (isset($_GET['food_category_id']))
{ 
    // Sanitize the id
    $food_category_id = filter_input(INPUT_GET, 'food_category_id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM foodcategories WHERE food_category_id = :food_category_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':food_category_id', $food_category_id, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $row = $statement->fetch();
}
else 
{
    $id = false; // False if we are not UPDATING or SELECTING.
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
    <title>Edit this Post!</title>
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
            <li><a href="create_category.php">New Category</a></li>
        </ul> 

        <div id="main_content">
            <form action="edit_category.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Edit Category</legend>
                    <p> 
                        <label for="food_category_name">Category Name</label>
                        <input class="form-control" name="food_category_name" id="food_category_name" value="<?= $row['food_category_name'] ?>"/>
                    </p>
                    <p>
                        <label for="food_category_description">Category Description</label>
                        <textarea class="form-control" name="food_category_description" id="food_category_description"><?= $row['food_category_description'] ?></textarea>
                    </p>

                    <?php if ($row['food_category_image'] != ""): ?>
                    <p>
                        <label for="food_category_image">Image File</label>
                        <input class="form-control" name="food_category_image" id="food_category_image" value="<?= $row['food_category_image'] ?>">
                        <input type="checkbox" id="delete_image" name="delete_image" value="delete">
                        <label for="delete_image"> Delete this image </label><br>
                    </p>
                    <?php endif ?>

                    <p>
                        <label for="image">Update Image Filename</label>
                        <input class="form-control" type="file" name="cat_image" id="cat_image">
                    </p>
                    
                    

                    <!-- Hidden input for the primary key -->
                    <input type="hidden" name="food_category_id" value="<?= $row['food_category_id'] ?>">
                    <?php if ($row['food_category_image'] == ""): ?>
                        <input type="hidden" name="food_category_image" value="<?= $row['food_category_image'] ?>">
                    <?php endif ?>
                    <p>
                        <button type="submit" class="btn btn-primary" name="command" value="Update">Update</button>
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