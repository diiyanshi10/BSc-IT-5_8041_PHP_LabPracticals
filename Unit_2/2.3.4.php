<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php

            //4-array_combine
            echo "<h3>1- array_combine()</h3>";
            $db=array("Id","Name","Age");
            $data=array("19","Chhagan","48");
            print_r(array_combine($db,$data));

        ?>
    </body>
</html>