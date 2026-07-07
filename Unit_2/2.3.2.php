<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            //2-array_chunk
            echo"<hr><h3>2 -array_chunk()</h3>";
            $months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
            print_r(array_chunk($months,3,"<br>"));
        ?>
    </body>
</html>