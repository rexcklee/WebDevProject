<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('connect.php');

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
    <?php include('Component\header.php'); ?>

    <div id="webpage" class="container-fluid">              

        <div id="index-page" class="container-md">

        <div id="carousel" class="my-4 carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="Image/index01.jpg" class="rounded d-block w-100" alt="food-image">
            </div>
            <div class="carousel-item">
                <img src="Image/index02.jpg" class="rounded d-block w-100" alt="food-image">
            </div>
            <div class="carousel-item">
                <img src="Image/index03.jpg" class="rounded d-block w-100" alt="food-image">
            </div>
            <div class="carousel-item">
                <img src="Image/index04.jpg" class="rounded d-block w-100" alt="food-image">
            </div>
        </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

            <p class="text-center text-success fs-3 fw-bold">Opening Hours</p>

            <p class="text-center text-success fs-3 fw-bold">Location</p>
        </div>
        
        <div id="footer">
            Copyright 2023 - Rights reserved by Rex
        </div> 
    
    </div> 
</body>
</html>