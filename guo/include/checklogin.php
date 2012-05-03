<?php
if ($_SESSION['boss'] == "" || $_SESSION['level'] != 1)
header("Location:login.php");
?>