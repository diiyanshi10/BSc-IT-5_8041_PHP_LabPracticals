<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            //7-array_unshift()
            echo "<h3>1- array_unshift()</h3>";
            $num =array(20,30);
            array_unshift($num,10);
            print_r($num);

        ?>
    </body>
</html>