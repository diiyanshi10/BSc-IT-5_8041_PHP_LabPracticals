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

            //2-strpos()
            echo "<h3>1. strpos()</h3>";
            echo "position of 'PHP'= ".strpos($str,"PHP");
        ?>
    </body>
</html>