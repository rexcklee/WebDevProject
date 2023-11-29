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
if ($_POST && user_create_input_is_valid())
{
    //  Sanitize user input to escape HTML entities and filter out dangerous characters.
    $username = filter_input(INPUT_POST, 'create_username', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'create_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'create_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $admin = ($_POST['admin_right'] == 'admin_right')? 1 : 0;

    //  Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO users (username, password, email, admin) VALUES (:username, :password, :email, :admin)";
    $statement = $db->prepare($query);
    
    //  Bind values to the parameters
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':email'   , $email   );
    $statement->bindValue(':admin'   , $admin   );
    
    //  Execute the INSERT.
    if($statement->execute())
    {
        header('Location: users.php');;
    }
}
// Handle input invalid error
else if ($_POST && !user_create_input_is_valid()) 
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
            <li><a href="create_category.php">New Category</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="create_user.php" class="active">New User</a></li>
        </ul> 

        <div id="main_content">
            <form action="create_user.php" method="post">
                <fieldset>
                    <legend>Create User</legend>                 
                        <p> 
                            <label for="create_username">Username:</label>
                            <input class="form-control" name="create_username" id="create_username" />
                        </p>
                        <p>
                            <label for="create_email">Email:</label>
                            <input class="form-control" name="create_email" id="create_email">
                        </p>
                        <p>
                            <label for="create_password">Password:</label>
                            <input type="password" class="form-control" name="create_password" id="create_password">
                        </p>
                        <p> 
                            <input type="checkbox" class="form-check-input" id="admin_right" name="admin_right" value="admin_right">
                            <label for="admin_right" class="form-check-label"> Admin right </label><br>
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