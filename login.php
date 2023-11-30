<?php

/*******w******** 
    
    Name: Chi Kin Lee
    Date: Nov 5, 2023
    Description: Web Dev 2 Final Project

****************/
require('connect.php');

if (isset($_POST['login'])){

    session_start();

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Build the parametrized SQL query using the filtered id.
    //$query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    //$statement->bindValue(':password', $password);
    $statement->execute();

    //if($row = $statement->fetch()){
    if(($row = $statement->fetch()) && (password_verify($password, $row['password'])))
    {
        $_SESSION['id']=$row['user_id'];
        $_SESSION['username']=strtoupper($row['username']);
        $_SESSION['admin']=$row['admin'];

        header('Location: index.php');
    }
    else{
        $login_error = true;
        session_destroy();
    }


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
            <?php if(isset($login_error)): ?>
                <p class="text-danger fs-3"> Login invalid! </p>
            <?php endif ?>
            <form action="login.php" method="post">
                <fieldset>
                    <legend class="mx-auto">LOGIN</legend>
                    <p> 
                        <label for="username">Username:</label>
                        <input class="form-control" name="username" id="username"/>
                    </p>
                    <p>
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </p>
                    <p>                       
                        <button type="submit" class="btn btn-primary" name="login">LOGIN</button>
                    </p>
                    <p> <a id="register" class="link-primary fw-bold" href="login_register.php">I am a new user, register now!</a></p>
                    
                </fieldset>
            </form>   
                    
        </div>

        <div id="footer">
            Copyright 2023 - Rights reserved by Rex
        </div> 
    </div> 
</body>
</html>