<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/

$header_cat_query = "SELECT * FROM foodcategories ORDER BY food_category_id";

    $header_cat_statement = $db->prepare($header_cat_query);

    $header_cat_statement->execute(); 

?>
    
    <nav class="navbar navbar-expand-xl bg-light navbar-light">
        <div class="container-fluid">
            <div class="justify-content-start">
                <img src="Image/Oishii02.png" alt="" width="100" height="100">
                <a href="index.php" id="home_icon" class="navbar-brand d-none d-lg-inline align-middle">Oishii Japanese Restaurant</a>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse justify-content-end" id="navbarText">

                <ul id="restaurant_menu" class="navbar-nav">
                    <li class="nav-item"><a id="menu_item" class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a id="menu_item" class="nav-link" href="menu_categories.php">MENU</a></li>
                    <li class="nav-item"><a id="menu_item" class="nav-link" href="contact_us.php">CONTACT</a></li>
                    <li class="nav-item"><a id="menu_item" class="nav-link" href="login.php">LOGIN</a></li>
                </ul>
                
                <form class="d-flex" action="dish_search.php" method="post" >

                    <input class="form-control" name="search_word" id="search_word" placeholder="Search"/>
                    
                    <select class="form-select" name="search_food_cat_id" id="search_food_cat_id" >
                        <option value=999 >Menu</option>                           
                        <?php while($header_cat_row = $header_cat_statement->fetch()): ?>
                            <option value="<?= $header_cat_row['food_category_id'] ?>"><?= $header_cat_row['food_category_name'] ?></option>
                        <?php endwhile ?>
                    </select>  
    
                    <button type="submit" class="btn btn-secondary" name="command">Search</button>
                            
                </form>
            </div>
        </div>
    </nav>

    