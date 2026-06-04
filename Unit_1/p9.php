<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program 9</title>
</head>
<body>
       <?php
            echo "Array Reversing <br>";
            $num=array(23,45,56,12);
            $rev=array_reverse($num);
            foreach($rev as $r)
            {
                echo "$r <br>";
            }
        ?>
</body>
</html>