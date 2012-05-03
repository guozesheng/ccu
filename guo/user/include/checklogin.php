<?php
if ($_SESSION['boss'] == "" || $_SESSION['level'] != 2)
header("Location:./../login.php");
?>