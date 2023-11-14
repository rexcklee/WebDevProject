<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('connect.php');

$sort_by = "m.food_category_id";
$sort_by_name = 'Category';

// Check sorting on which column
if (isset($_POST['command']))
{
    if ($_POST['command']=='Sort')
    {
        $sort_by = $_POST['sorting'];
        switch ($sort_by) 
        {
            case "dish_name":
                $sort_by_name = 'Dish';
                break;
            case "dish_prices":
                $sort_by_name = 'Price';
                break;
            case "m.food_category_id":
                $sort_by_name = 'Category';
                break;
        }
    }
}

#$query_cat = "SELECT * FROM menuitems m JOIN foodcategories f ON m.food_category_id = f.food_category_id ORDER BY $sort_by";
$query_cat = "SELECT * FROM foodcategories";

$statement_cat_query = $db->prepare($query_cat);

$statement_cat_query->execute(); 

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

        <ul id="restaurant_menu">
            <li><a href="index.php">Oishii</a></li>
            <li><a href="menu_categories.php">Categories</a></li>
            <li><a href="contact_us.php">Contact Us</a></li>
        </ul> 

        <div id="main_menu">

            <!-- table of all categories -->
            <table class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Category</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $row_no = 0; ?>
                    <?php while($row = $statement_cat_query->fetch()): ?>
                        <?php $row_no ++; ?>                       
                        <tr>
                            <th scope="row"><?= $row_no ?></th>
                            <td><a id="food-category" href="menu_dishes.php?food_category_id=<?= $row['food_category_id'] ?>"><?= $row['food_category_name'] ?></a></td>
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