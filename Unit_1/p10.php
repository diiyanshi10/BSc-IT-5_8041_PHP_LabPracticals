<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program 10</title>
</head>
<body>
    <?php
        echo "Merge of Arrays";
        $ar1=array(10,23,45,75);
        $ar2=array(10,75,34,23);
        $mer=array_intersect($ar1,$ar2);
        foreach($mer as $m)
        {
            echo $m.x"<br>";
        }
    ?>
</body>
</html>