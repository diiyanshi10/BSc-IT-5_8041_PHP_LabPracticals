<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>program 5</title>
    </head>
    <body>
        <?php
            echo "For loop<br>";
            $i=5;
            for($i;$i<=10;$i++)
            {
                echo "$i <br>";
            }
            $arr=array(1,4,3);
            echo "Foreach loop<br>";
            foreach($arr as $a)
            {
                echo "$a <br>";
            }
        ?>
    </body>
</html>