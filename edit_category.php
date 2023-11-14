<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('authenticate.php');
require('connect.php');
require('validation.php');

// Handle UPDATE
if (isset($_POST['command']))
{
    // Handle category UPDATE
    if (($_POST['command']=='Update') && input_is_valid() && isset($_POST['food_category_id']))
    {    
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $food_category_name = filter_input(INPUT_POST, 'food_category_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $food_category_description = filter_input(INPUT_POST, 'food_category_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $food_category_id = filter_input(INPUT_POST, 'food_category_id', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE foodcategories SET food_category_name = :food_category_name, food_category_description = :food_category_description WHERE food_category_id = :food_category_id";

        $statement = $db->prepare($query);

        //  Bind values to the parameters
        $statement->bindValue(':food_category_name', $food_category_name);
        $statement->bindValue(':food_category_description', $food_category_description);
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
            <form action="edit_category.php" method="post">
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

                    <!-- Hidden input for the primary key -->
                    <input type="hidden" name="food_category_id" value="<?= $row['food_category_id'] ?>">

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