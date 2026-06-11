<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
        function adder($str2)
        {
            $str2.='call by value';
        }
        $str='this is:' ;
        adder($str);
        echo $str;
    ?>
</body>
</html>