<?php
   session_start();
   unset($_SESSION["username"]);
   unset($_SESSION["password"]);
   
   echo 'You have cleaned session. See you soon...';
   header('Refresh: 2; URL = login.php');
?>