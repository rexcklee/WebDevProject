<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('authenticate.php');
require('connect.php');
require('validation.php');

// Handle UPDATE and DELETE
if (isset($_POST['command']))
{
    // Handle dish DELETE
    if ($_POST['command']=='Delete' && isset($_POST['dish_id']))
    {
        $dish_id = filter_input(INPUT_POST, 'dish_id', FILTER_SANITIZE_NUMBER_INT);
        $query = "DELETE FROM menuitems WHERE dish_id = :dish_id LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindValue(':dish_id', $dish_id, PDO::PARAM_INT);
        $statement->execute();
        // Direct to admin page after delete.
        header("Location: admin.php");
    }
    // Handle dish UPDATE
    else if (($_POST['command']=='Update') && input_is_valid() && isset($_POST['dish_id']))
    {    
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $dish_name = filter_input(INPUT_POST, 'dish_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dish_description = filter_input(INPUT_POST, 'dish_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dish_prices = filter_input(INPUT_POST, 'dish_prices', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $food_category_id = filter_input(INPUT_POST, 'food_category_id', FILTER_SANITIZE_NUMBER_INT);
        $dish_id      = filter_input(INPUT_POST, 'dish_id', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE menuitems SET dish_name = :dish_name, dish_description = :dish_description , dish_prices = :dish_prices, food_category_id = :food_category_id WHERE dish_id = :dish_id";

        $statement = $db->prepare($query);

        //  Bind values to the parameters
        $statement->bindValue(':dish_name', $dish_name);
        $statement->bindValue(':dish_description', $dish_description);
        $statement->bindValue(':dish_prices', $dish_prices);
        $statement->bindValue(':food_category_id', $food_category_id);
        $statement->bindValue(':dish_id', $dish_id, PDO::PARAM_INT);
        
        // Execute the INSERT.
        $statement->execute();
        
        // Direct to admin page after update.
        header("Location: admin.php");
        exit;
    }
    // Handle input invalid error
    else if (($_POST['command']=='Update') && !input_is_valid() && isset($_POST['dish_id']))
    {      
        header('Location: error.php');
    }
}

// Retrieve dish information to be EDIT, if id GET parameter is in URL.
if (isset($_GET['dish_id']))
{ 
    // Sanitize the id
    $dish_id = filter_input(INPUT_GET, 'dish_id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM menuitems m JOIN foodcategories f ON m.food_category_id = f.food_category_id WHERE m.dish_id = :dish_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':dish_id', $dish_id, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $row = $statement->fetch();

    // Query for the food Categories
    $query_cat = "SELECT * FROM foodcategories ORDER BY food_category_id";
    $statement_cat = $db->prepare($query_cat);
    $statement_cat->execute(); 
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
            <li><a href="users.php">Users</a></li>
            <li><a href="create_user.php">New User</a></li>
            <li><a href="admin_user_comment.php">User Comments</a></li>
        </ul> 

        <div id="main_content">
            <form action="edit_dish.php" method="post">
                <fieldset>
                    <legend>Edit Dish</legend>
                    <p> 
                        <label for="dish_name">Dish Name</label>
                        <input class="form-control" name="dish_name" id="dish_name" value="<?= $row['dish_name'] ?>"/>
                    </p>
                    <p>
                        <label for="dish_description">Dish Description</label>
                        <textarea class="form-control" name="dish_description" id="dish_description"><?= $row['dish_description'] ?></textarea>
                    </p>
                    <p> 
                        <label for="dish_prices">Dish Price</label>
                        <input class="form-control" name="dish_prices" id="dish_prices" value="<?= $row['dish_prices'] ?>"/>
                    </p>
                    <p> 
                        <label for="food_category_id">Category ID</label>
                        <select class="form-select" name="food_category_id" id="food_category_id" >
                            <option value="<?= $row['food_category_id'] ?>" selected><?= $row['food_category_name'] ?></option>                           
                                <?php while($row_cat = $statement_cat->fetch()): ?>
                                <option value="<?= $row_cat['food_category_id'] ?>"><?= $row_cat['food_category_name'] ?></option>
                                <?php endwhile ?>
                        </select>
                    </p>

                    <!-- Hidden input for the primary key -->
                    <input type="hidden" name="dish_id" value="<?= $row['dish_id'] ?>">

                    <p>
                        <button type="submit" class="btn btn-primary" name="command" value="Update">Update</button>
                        <button type="submit" class="btn btn-primary" name="command" value="Delete">Delete</button>
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