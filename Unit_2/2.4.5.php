<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            $str="Welcome to PHP";
            echo "<h2>PHP string functions</h2>";

            //5-str_replace()
            echo "<h3>1. str_replace()</h3>";
            echo str_replace("PHP","Java",$str);
            
        ?>
    </body>
</html>