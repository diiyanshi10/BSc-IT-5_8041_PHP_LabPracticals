<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            echo "Using associative array to create week <br>";
            $month=array("Jan"=>"31","Feb"=>"28","Mar"=>"31","Apr"=>"30","May"=>"31","Jun"=>"30","July"=>"31","Aug"=>"31","Sep"=>"30","Oct"=>"31","Nov"=>"30","Dec"=>"31");
            foreach($month as $m)
            {
                echo $m."<br>";
            }
            echo "Days in Deccember: ".$month["Dec"];
        ?>
    </body>
</html>