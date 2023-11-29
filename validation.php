<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/

// Check if input title and content is valid
function input_is_valid() 
{
    $form_is_valid = true;
    /*
    Code later
    */
    return $form_is_valid;
}

function register_input_is_valid() 
{
    $input_is_valid = true;

    $username = $_POST["username"];
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];

    if (!filter_var($username, FILTER_VALIDATE_EMAIL) || 
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

?>