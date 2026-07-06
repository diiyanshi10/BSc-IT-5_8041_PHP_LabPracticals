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

            //1-strlen()
            echo "<h3>1. strlen()</h3>";
            echo "String: $str <br>";
            echo "Length = ".strlen($str);

        ?>
    </body>
</html>