<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('connect.php');

if ($_POST)
{ 
    $search_word = filter_input(INPUT_POST, 'search_word', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $search_food_cat_id = filter_input(INPUT_POST, 'search_food_cat_id', FILTER_SANITIZE_NUMBER_INT);

    if ($search_food_cat_id != 999){
    $query = "SELECT * FROM menuitems m JOIN foodcategories f ON m.food_category_id = f.food_category_id WHERE m.food_category_id = :search_food_cat_id AND m.dish_name LIKE :search_word";

    $statement = $db->prepare($query);
    
    //  Bind values to the parameters
    $statement->bindValue(':search_word', '%'.$search_word.'%');
    $statement->bindValue(':search_food_cat_id', $search_food_cat_id);
    }
    else{
        $query = "SELECT * FROM menuitems WHERE dish_name LIKE :search_word";
        $statement = $db->prepare($query);
        $statement->bindValue(':search_word', '%'.$search_word.'%');
    }
  
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
    <title>Edit this Post!</title>
</head>
<body>
    <?php include('Component\header.php'); ?>  

    <div id="webpage" class="container-fluid">

        <div id="main_menu" class="container-md">
         
            <p class="text-center text-black fs-1 fw-bold"> SEARCH RESULT </p>

            <!-- table of all categories -->
            <table id="menu_table" class="table table-warning table-borderless table-hover">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Dish Name</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $row_no = 0; ?>
                    <?php while($row = $statement->fetch()): ?>
                        <?php $row_no ++; ?>                       
                        <tr>
                            <th scope="row"><?= $row_no ?></th>
                            <td><a class="text-decoration-none text-dark" id="dish-name" href="menu_dish_page.php?dish_id=<?= $row['dish_id'] ?>"><?= $row['dish_name'] ?></a></td>
                            <td><a class="text-decoration-none text-dark" id="dish-name" href="menu_dish_page.php?dish_id=<?= $row['dish_id'] ?>">$<?= $row['dish_prices'] ?></a></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>

        </div>

        <div id="footer">
            Copyright 2023 - Rights reserved by Rex
        </div>

    </div> 
</body>
</html>