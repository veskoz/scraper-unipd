<?php
if (!empty(filter_input(INPUT_GET, 'act'))) {
    include 'functions.php';
    //setErrors();
    createTables();
    getUnits(); 
   
} else {
    
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title> asd </title>
    <body>
        <h2>Scraper-unipd</h2>
        <form action="index.php" method="get">
            <input type="hidden" name="act" value="run">
            <input type="submit" value="Create tables">
        </form>
    </head>
</body>
</html>