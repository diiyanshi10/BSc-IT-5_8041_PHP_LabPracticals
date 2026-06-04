<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Program 3 </title>
    </head>
    <body>
        <?php
            $n=10;
            function local_var()
            {
                $n=45;
                echo "Local variable is declared inside the function is:".$n."<br>";
                global $n;
                echo "Global variable is declared inside the function is:".$n;
            }
            local_var();
        ?>
    </body>
    </html>