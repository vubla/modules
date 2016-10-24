<?php
    header("Content-type: text/javascript");
    echo file_get_contents('https://api.vubla.com/scripts/?host='.urlencode($_SERVER['HTTP_HOST'])."&id=".$_GET['id']);
?>