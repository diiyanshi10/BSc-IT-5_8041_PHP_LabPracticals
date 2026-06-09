<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>program 8.1</title>
    </head>
    <body>
        <?php
            error_reporting(0);
        ?>
        <form method="get">
            <input type ="text" name="name1" >
            <input type ="text" name="name2" >
            <input type ="text" name="name3" >
            <input type ="text" name="name4" >
            <input type ="submit"  name="submit" value="Submit">                          
        </form>

        <?php
            echo "<H1> User Input for array <br>";
            $nm=array();
            $nm[0]=$_GET['name1'];
            $nm[1]=$_GET['name2'];
            $nm[2]=$_GET['name3'];
            $nm[3]=$_GET['name4'];
            
            foreach($nm as $n)
            {
                echo $n;
            }
        ?>
    </body>
</html>