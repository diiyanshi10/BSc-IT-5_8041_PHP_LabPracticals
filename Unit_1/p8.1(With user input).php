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
            <input type ="text" name="name[0]" >
            <input type ="text" name="name[1]" >
            <input type ="text" name="name[2]" >
            <input type ="text" name="name[3]" >
            <input type ="text" name="name[4]" >
            <input type ="submit"  name="submit" value="Submit">                          
        </form>

        <?php
            echo "<H1> User Input for array <br>";
        
            $nm=$_GET['name'];
            
            foreach($nm as $n)
            {
                echo $n;
            }
        ?>
    </body>
</html>