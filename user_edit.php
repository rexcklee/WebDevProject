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
    // Handle user DELETE
    if ($_POST['command']=='Delete' && isset($_POST['user_id']))
    {
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
        $query = "DELETE FROM users WHERE user_id = :user_id LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $statement->execute();
        // Direct to users page after delete.
        header("Location: users.php");
    }
    // Handle user UPDATE
    else if (($_POST['command']=='Update') && user_edit_input_is_valid() && isset($_POST['user_id']))
    {   
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $username = filter_input(INPUT_POST, 'create_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'create_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'create_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $admin = ($_POST['admin_right'] == 'admin_right')? 1 : 0;
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

        if ($password !== "")
        {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $query     = "UPDATE users SET username = :username, password = :password, email = :email, admin = :admin WHERE user_id = :user_id";
        }
        else 
        {
            $query     = "UPDATE users SET username = :username, email = :email, admin = :admin WHERE user_id = :user_id";
        }
        $statement = $db->prepare($query);

        //  Bind values to the parameters
        if ($password !== "")
        {
            $statement->bindValue(':password', $password_hashed);
        }
        $statement->bindValue(':username', $username);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':admin', $admin);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        
        // Execute the INSERT.
        $statement->execute();
        
        // Direct to users page after update.
        header("Location: users.php");
        exit;
    }
    // Handle input invalid error
    else if (($_POST['command']=='Update') && !user_edit_input_is_valid() && isset($_POST['user_id']))
    {      
        header('Location: error.php');
    }
}

// Retrieve category information to be EDIT, if id GET parameter is in URL.
if (isset($_GET['user_id']))
{ 
    // Sanitize the id
    $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM users WHERE user_id = :user_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    
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
            <li><a href="users.php">Users</a></li>
            <li><a href="create_user.php">New User</a></li>
        </ul> 

        <div id="main_content">
            <form action="user_edit.php" method="post">
                <fieldset>
                    <legend>Edit User</legend>
                    <p> 
                        <label for="create_username">Username</label>
                        <input class="form-control" name="create_username" id="create_username" value="<?= $row['username'] ?>"/>
                    </p>
                    <p>
                        <label for="create_password">New Password</label>
                        <input class="form-control" name="create_password" id="create_password"/>
                    </p>
                    <p>
                        <label for="create_email">Email</label>
                        <input class="form-control" name="create_email" id="create_email" value="<?= $row['email'] ?>"/>
                    </p>
                
                    <p>
                        <?php if ($row['admin'] == 1): ?> 
                            <input type="checkbox" class="form-check-input" id="admin_right" name="admin_right" value="admin_right" checked>
                        <?php else: ?>
                            <input type="checkbox" class="form-check-input" id="admin_right" name="admin_right" value="admin_right">
                        <?php endif ?>
                        <label for="admin_right" class="form-check-label"> Admin right </label><br>
                    </p>
                    
                    <!-- Hidden input for the primary key -->
                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">

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