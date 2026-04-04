<?php
include("flags.php");
if(isset($_GET['level'])){
    $level = $_GET['level'];
    if(isset($flags[$level])){
        echo $flags[$level];
    }
}
?>