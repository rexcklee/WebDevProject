 <?php

  session_start();

  if (!isset($_SESSION['admin']) || ($_SESSION['admin']!==1))
  {
      header('Location: error.php');
  } 

?>