<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>program 4.5</title>
    </head>
    <body>
        <?php
            echo"1. May <br> 2. June <br> 3. July <br>" ;
            $num=1;
            switch($num)
            {
                case 1:
                    echo "the month is May";
                    break;
                case 2:
                    echo"The month is June";
                    break;
                case 3:
                    echo "The month is July";
                    break;
                default:
                    echo "Incorrect Month";
            }
        ?>
    </body>
</html>