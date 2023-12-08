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
    // Handle dish DELETE
    if ($_POST['command']=='Delete' && isset($_POST['comment_id']))
    {
        $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
        $query = "DELETE FROM user_comment WHERE comment_id = :comment_id LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        $statement->execute();
        // Direct to admin page after delete.
        header("Location: admin_user_comment.php");
    }
    // Handle category UPDATE
    if (($_POST['command']=='Update') && isset($_POST['comment']))
    {   
        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $display = filter_input(INPUT_POST, 'display', FILTER_SANITIZE_NUMBER_INT);
        $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $query     = "UPDATE user_comment SET comment = :comment, display = :display WHERE comment_id = :comment_id";

        $statement = $db->prepare($query);

        //  Bind values to the parameters
        $statement->bindValue(':comment', $comment);
        $statement->bindValue(':display', $display);
        $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        
        // Execute the INSERT.
        $statement->execute();
        
        // Direct to admin page after update.
        header("Location: admin_user_comment.php");
        exit;
    }
}
// Handle input invalid error
else if (isset($_POST['command']) && ($_POST['food_category_name'] == ""))
{      
    header('Location: error.php');
}

// Retrieve category information to be EDIT, if id GET parameter is in URL.
if (isset($_GET['comment_id']))
{ 
    // Sanitize the id
    $comment_id = filter_input(INPUT_GET, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM user_comment WHERE comment_id = :comment_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
    
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
            <li><a href="admin_user_comment.php">User Comments</a></li>
        </ul> 

        <div id="main_content">
            <form action="admin_user_comment_edit.php" method="post">
                <fieldset>
                    <legend>Edit Comment</legend>
                    <p>
                        <label for="comment">Comment</label>
                        <textarea class="form-control" name="comment" id="comment"><?= $row['comment'] ?></textarea>
                    </p>

                    <p>
                        <input type="checkbox" id="display" name="display" value="1" <?= ($row['display'] == 1)? "checked" : "" ?>>
                        <label for="display"> Display this comment </label><br>
                    </p>
                    
                    <!-- Hidden input for the primary key -->
                    <input type="hidden" name="comment_id" value="<?= $row['comment_id'] ?>">
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