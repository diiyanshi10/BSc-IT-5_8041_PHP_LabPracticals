<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php 
            echo "Arithmetic <br>";
            $a=10;
            $b=20;
            $c=$a+$b;
            $d=$a-$b;
            $e=$a/$b;
            $f=$a*$b;
            $g=$a%$b;
            echo"Addition of a and b :$c <br>";
            echo"Subtraction of a and b :$d<br>";
            echo"Division of a and b :$e <br>";
            echo"Multiplication of a and b :$f <br>";
            echo"Remainder of a and b :$g <br><br>";

            echo"Assignment <br>";
            $h=10;
            $c+=$h;
            $d-=$h;
            $e/=$h;
            $f*=$h;
            $g%=$h;
            echo"Addition Assignment of a and b :$c <br>";
            echo"Subtraction Assignment of a and b :$d<br>";
            echo"Division Assignment of a and b :$e <br>";
            echo"Multiplication Assignment of a and b :$f <br>";
            echo"Remainder Assignment of a and b :$g <br>";

            echo"Relational <br>";
            if($a==$b)
            {
                echo "a is equal to b <br>";
            }
            else
            {
                echo "a is not equal to b <br>";
            }

             if($a!=$b)
            {
                echo "a is not equal to b <br>";
            }
            else
            {
                echo "a is equal to b <br>";
            }

             if($a>$b)
            {
                echo "a is greater than b <br>";
            }
            else
            {
                echo "a is not greater than b <br>";
            }

             if($a<$b)
            {
                echo "a is less than b <br>";
            }
            else
            {
                echo "a is not less than b <br>";
            }

             if($a>=$b)
            {
                echo "a is greater than or equal to b <br>";
            }
            else
            {
                echo "a is not greater than or equal to b <br>";
            }

             if($a<=$b)
            {
                echo "a is less than or equal to b <br>";
            }
            else
            {
                echo "a is not less than or equal to b <br>";
            }
            

        ?>
    </body>
</html>