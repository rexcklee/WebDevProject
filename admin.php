<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('authenticate.php');
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

$query = "SELECT * FROM menuitems m JOIN foodcategories f ON m.food_category_id = f.food_category_id ORDER BY $sort_by";

$statement = $db->prepare($query);

$statement->execute(); 

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
            <li><a href="admin.php" class="active">Food Items</a></li>
            <li><a href="create_dish.php">New Dish</a></li>
            <li><a href="food_categories.php">Categories</a></li>
            <li><a href="create_category.php">New Category</a></li>
        </ul> 

        <div id="main_content">

            <!-- table of all food items -->
            <table class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Dish</th>
                    <th scope="col">Price</th>
                    <th scope="col">Category</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $row_no = 0; ?>
                    <?php while($row = $statement->fetch()): ?>
                        <?php $row_no ++; ?>                       
                        <tr>
                            <th scope="row"><?= $row_no ?></th>
                            <td><?= $row['dish_name'] ?></td>
                            <td><?= $row['dish_prices'] ?></td>
                            <td><?= $row['food_category_name'] ?></td>
                            <td><a id="edit-button" href="edit_dish.php?dish_id=<?= $row['dish_id'] ?>">Edit</a></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
            
            <!-- selection of sorting -->
            <div id="sort_select">
                <form id="sort_select" action="admin.php" method="post">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label" for="sorting">List is sorted by </label>
                            <select class="form-control" name="sorting" id="sorting" >
                                <option value="m.food_category_id" selected><?= $sort_by_name ?></option>                                                              
                                <option value="dish_name">Dish</option>
                                <option value="dish_prices">Price</option>
                                <option value="m.food_category_id">Category</option>
                            </select>
                        <button type="submit" class="btn btn-primary" name="command" value="Sort">Sort</button>
                    </div>
                </form>
            </div>

        </div>
        
        <div id="footer">
            Copyright 2023 - Rights reserved by Rex
        </div> 
    </div> 
</body>
</html>