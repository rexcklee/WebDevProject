<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/

// Check if input title and content is valid
function input_is_valid() 
{
    $input_is_valid = true;
    
    $dish_name        = $_POST["dish_name"];
    $dish_prices      = $_POST["dish_prices"];
    $food_category_id = $_POST["food_category_id"];

    if (($dish_prices < 0) || !filter_var($dish_prices, FILTER_VALIDATE_FLOAT) ||
        ($dish_name == null) ||(strlen(str_replace($dish_name,""," ") == 0))||
        !filter_var($food_category_id, FILTER_VALIDATE_INT))
    {
        $input_is_valid = false;
    }

    return $input_is_valid;
}

function register_input_is_valid() 
{
    $input_is_valid = true;

    $username = $_POST["username"];
    $email    = $_POST["email"];
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];

    if (($username == null) || !filter_var($email, FILTER_VALIDATE_EMAIL) || 
        (strlen($password) < 6) || 
        ($password !== $password_confirm))
        {
            $input_is_valid = false;
        }

    /*
    Code later
    */
    return $input_is_valid;
}

function user_create_input_is_valid() 
{
    $input_is_valid = true;

    $username = $_POST["create_username"];
    $password = $_POST["create_password"];
    $email    = $_POST["create_email"];

    if (($username == null) || !filter_var($email, FILTER_VALIDATE_EMAIL) || 
        (strlen($password) < 6))
        {
            $input_is_valid = false;
        }

    return $input_is_valid;
}

function user_edit_input_is_valid() 
{
    $input_is_valid = true;

    $username = $_POST["create_username"];
    $password = $_POST["create_password"];
    $email    = $_POST["create_email"];

    if (($username == null) || !filter_var($email, FILTER_VALIDATE_EMAIL) || 
        ((strlen($password) < 6) && ($password !== "")))
        {
            $input_is_valid = false;
        }

    return $input_is_valid;
}
?>