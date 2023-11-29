<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('connect.php');
require('validation.php');

// Create new user if input is valid, else direct to error message page.
if ($_POST && register_input_is_valid())
{
    //  Sanitize user input to escape HTML entities and filter out dangerous characters.
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    //  Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $statement = $db->prepare($query);
    
    //  Bind values to the parameters
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    
    //  Execute the INSERT.
    if($statement->execute())
    {
        header('Location: login.php');;
    }
}
// Handle input invalid error
else if ($_POST && !register_input_is_valid()) 
{
    $input_error = true;
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

        <div id="login" class="container-sm">
            <?php if(isset($input_error)): ?>
                <p class="text-danger fs-3"> Registration error! Please try again! </p>
            <?php endif ?>
            <form action="login_register.php" method="post">
                <fieldset>
                    <legend>USER REGISTRATION</legend>
                    <p> 
                        <label for="username">Email:</label>
                        <input class="form-control" name="username" id="username" />
                    </p>
                    <p>
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </p>
                    <p>
                        <label for="password_confirm">Confirm password:</label>
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm"></textarea>
                    </p>
                    <p>                       
                        <button type="submit" class="btn btn-primary" name="command">REGISTER</button>
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