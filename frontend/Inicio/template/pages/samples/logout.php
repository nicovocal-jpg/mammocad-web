<?php 
session_start();
session_unset();
session_destroy();
header('location:/Proyecto3/frontend/Inicio/template/pages/samples/login.php');
 ?>