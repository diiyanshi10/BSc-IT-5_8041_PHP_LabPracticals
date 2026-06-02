<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Program 4.5: Nested If-Else </title>
    </head>
    <body>
        <?php
            $month="June";
            $date=2;
            if($month=="June")
            {
                if($date==2)
                {
                    $date=3;
                    echo "Date changed to 3 <br>";
                    echo "Therefore the date is: ".$date."-".$month;
                }
                else
                {
                    echo "The date is not 2";
                }
            }
            else
            {
                echo "The Month is not June";
            }
        ?>   
    </body>
</html>