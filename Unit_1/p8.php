<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Program 8</title>
    </head>
    <body>
        <?php
            echo "Array Printing <br>";
            $bsc_it=array("diyanshi","piyu","krisha","kavya");
            echo "Students of Bsc (IT)<br>";
            foreach($bsc_it as $stud)
            {
               
                echo "$stud <br>";
            }
        ?>
    </body>
</html>