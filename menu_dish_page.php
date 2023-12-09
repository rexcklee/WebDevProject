<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('connect.php');
require('validation.php');

// Retrieve dish information to be EDIT, if id GET parameter is in URL.
if (isset($_GET['dish_id']))
{ 
    // Sanitize the id
    $dish_id = filter_input(INPUT_GET, 'dish_id', FILTER_SANITIZE_NUMBER_INT);
    
    // Build the parametrized SQL query using the filtered id.
    $query = "SELECT * FROM menuitems WHERE dish_id = :dish_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':dish_id', $dish_id, PDO::PARAM_INT);
    
    // Execute the SELECT and fetch the single row returned.
    $statement->execute();
    $row = $statement->fetch();

    // Get comment
    $query_comment = "SELECT * FROM user_comment c JOIN users u ON c.user_id = u.user_id WHERE c.dish_id = :dish_id_comment AND c.display = 1 ORDER BY create_date DESC";
    $statement_comment = $db->prepare($query_comment);
    $statement_comment->bindValue(':dish_id_comment', $dish_id);       

    $statement_comment->execute();
}

if ($_POST && comment_is_valid())
{
//  Sanitize user input to escape HTML entities and filter out dangerous characters.
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dish_id = filter_input(INPUT_POST, 'dish_id', FILTER_SANITIZE_NUMBER_INT);
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
$display = 1;

//  Build the parameterized SQL query and bind to the above sanitized values.
$query = "INSERT INTO user_comment (comment, dish_id, user_id, display) VALUES (:comment, :dish_id, :user_id, :display)";
$statement = $db->prepare($query);

//  Bind values to the parameters
$statement->bindValue(':comment', $comment);
$statement->bindValue(':dish_id', $dish_id);
$statement->bindValue(':user_id', $user_id);
$statement->bindValue(':display', $display);

//  Execute the INSERT.
if($statement->execute())
{
    header('Location: menu_dish_page.php?dish_id='.$dish_id);;
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
    <title>Edit this Post!</title>
</head>
<body>
<?php include('Component\header.php'); ?>    
    <div id="webpage" class="container-fluid">

        <div id="main_menu" class="container-md">
            <div class="d-flex justify-content-between">
                <p class="text-start my-3 fs-1 fw-bold"><?= $row['dish_name'] ?></p>
                <p class="text-start my-3 fs-1 fw-bold">$<?= $row['dish_prices'] ?></p>
            </div>
            <p class="text-start fs-2"><?= $row['dish_description'] ?></p>  
            
            
            <!-- Display all comments -->           
            <?php if($statement_comment->rowCount() > 0): ?>
            <div class="card border-warning text-bg-warning w-100 mb-3 mt-5">
                <div class="card-header border-warning text-bg-warning text-center fs-2">
                    OUR CUSTOMER SAY
                </div>
                <?php while($row_comment = $statement_comment->fetch()): ?>
                    <div class="card-body rounded border-warning text-bg-secondary fs-3 m-2">
                        <h3 class="card-title"><?= strtoupper($row_comment['username']) ?>:</h5>
                        <p class="card-text"><?= $row_comment['comment'] ?></p>
                    </div>
                <?php endwhile ?>
            </div>
            <?php endif ?> 

            <?php if(isset($_SESSION['id'])): ?>
            <!-- Comment submittion form -->
            <form action="menu_dish_page.php" method="post">
                <fieldset>
                    <legend>YOUR COMMENT:</legend>                 
                        <p>
                            <textarea class="form-control" name="comment" id="comment"></textarea>
                        </p>
                        
                        <!-- Hidden input for the dish_id and user_id -->
                        <input type="hidden" name="dish_id" value="<?= $row['dish_id'] ?>">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?>">

                        <p>                       
                            <button type="submit" class="btn btn-primary" name="command">Submit</button>
                        </p>
                </fieldset>
            </form>
            <?php endif ?>  
        </div>

        

        <div id="footer">
            Copyright 2023 - Rights reserved by Rex
        </div> 
    </div> 
</body>
</html>