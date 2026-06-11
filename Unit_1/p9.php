<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Program 9</title>
    </head>
    <body>
        <?php
            error_reporting(0);
        ?>
            <form method="get">
                <input type ="text" name="name[0]" >
                <input type ="text" name="name[1]" >
                <input type ="text" name="name[2]" >
                <input type ="submit"  name="submit" value="Submit">                          
            </form>
        <?php
            echo "Array Reversing <br>";
            $num=$_GET['name'];
            $rev=array_reverse($num);
            foreach($rev as $r)
            {
                echo "$r <br>";
            }
        ?>
    </body>
</html>