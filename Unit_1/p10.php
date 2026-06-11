<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Program 10</title>
    </head>
    <body>
        <?php
            error_reporting(0);
        ?>
        <form method="get">
            <h2> Array1 </h2>
            <input type ="text" name="ar1[0]" >
            <input type ="text" name="ar1[1]" >
            <input type ="text" name="ar1[2]" >
            <br>
            <h2> Array2 </h2>
            <br>
            <input type ="text" name="ar2[0]" >
            <input type ="text" name="ar2[1]" >
            <input type ="text" name="ar2[2]" >
            <br>
            <input type ="submit"  name="submit" value="Submit">                          
            </form>
        <?php
            echo "Merge of Arrays";
            $ar1=$_GET['ar1'];
            $ar2=$_GET['ar2'];
            $mer=array_merge($ar1,$ar2);
            foreach($mer as $m)
            {
                echo $m."<br>";
            }
        ?>
    </body>
</html>