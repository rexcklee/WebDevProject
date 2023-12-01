<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('authenticate.php');
require('connect.php');
require('validation.php');

// Insert new row if input is valid, else direct to error message page.
if ($_POST && input_is_valid())
{
    //  Sanitize user input to escape HTML entities and filter out dangerous characters.
    $dish_name = filter_input(INPUT_POST, 'dish_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dish_description = filter_input(INPUT_POST, 'dish_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dish_prices = filter_input(INPUT_POST, 'dish_prices', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $food_category_id = filter_input(INPUT_POST, 'food_category_id', FILTER_SANITIZE_NUMBER_INT);
    
    //  Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO menuitems (dish_name, dish_description, dish_prices, food_category_id) VALUES (:dish_name, :dish_description, :dish_prices, :food_category_id)";
    $statement = $db->prepare($query);
    
    //  Bind values to the parameters
    $statement->bindValue(':dish_name', $dish_name);
    $statement->bindValue(':dish_description', $dish_description);
    $statement->bindValue(':dish_prices', $dish_prices);
    $statement->bindValue(':food_category_id', $food_category_id);
    
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
// Obtain Food categories from "foodcategories" for selection
else 
{
    $query = "SELECT * FROM foodcategories ORDER BY food_category_id";

    $statement = $db->prepare($query);

    $statement->execute(); 
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
            <li><a href="create_dish.php" class="active">New Dish</a></li>
            <li><a href="food_categories.php">Categories</a></li>
            <li><a href="create_category.php">New Category</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="create_user.php">New User</a></li>
        </ul> 

        <div id="main_content">
            <form action="create_dish.php" method="post">
                <fieldset>
                    <legend>New Dish</legend>                 
                        <p> 
                            <label for="dish_name">Dish Name</label>
                            <input class="form-control" name="dish_name" id="dish_name" />
                        </p>
                        <p>
                            <label for="dish_description">Dish Description</label>
                            <textarea class="form-control" name="dish_description" id="dish_description"></textarea>
                        </p>
                        <p> 
                            <label for="dish_prices">Dish Price</label>
                            <input class="form-control" name="dish_prices" id="dish_prices" />
                        </p>
                        <p> 
                            <label for="food_category_id">Category ID</label>
                            <select class="form-select" name="food_category_id" id="food_category_id" >
                                <option selected>-- Food_category --</option>                           
                                <?php while($row = $statement->fetch()): ?>
                                    <option value="<?= $row['food_category_id'] ?>"><?= $row['food_category_name'] ?></option>
                                <?php endwhile ?>
                            </select>
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