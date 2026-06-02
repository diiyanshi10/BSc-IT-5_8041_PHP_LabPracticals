<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Program 6</title>
    </head>
    <body>
        <?php
            $i=15;
            echo "With while loop";
            while($i<=20)
            {
                echo "$i <br>";
                $i++;
            }

            $i=15;
            echo "With Do while";
            do
            {
                echo "$i <br>";
                $i++;
            }
            while($i<=20);

        ?>
    </body>
</html>