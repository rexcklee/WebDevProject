<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('connect.php');

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
<?php include('Component\header.php'); ?>    
    <div id="webpage" class="container-fluid">

    <div id="main_menu" class="container-md">
                    <div class="d-flex justify-content-between">
                    <p class="text-start my-4 fs-1 fw-bold"><?= $row['dish_name'] ?></p>
                    <p class="text-start my-4 fs-1 fw-bold">$<?= $row['dish_prices'] ?></p>
                    </div>
                    <p class="text-start fs-2"><?= $row['dish_description'] ?></p>
                    
        </div>

        <div id="footer">
            Copyright 2023 - Rights reserved by Rex
        </div> 
    </div> 
</body>
</html>