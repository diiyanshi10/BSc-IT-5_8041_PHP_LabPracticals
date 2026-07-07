<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            $var = "12345";
            echo "<b>Original Value: </b> ".$var."<br>";
            echo "<b>Original Data Type:<b/> ".gettype($var)."<br><br>";

            settype($var,"integer");
            echo "<b> After Type casting:<b> ".$var."<br";
            echo "<b>New Data Type:</b> ".gettype($var)."<br><br>";

            settype($var,"double");
            echo "<b> After converting to Double:<b> ".$var."<br";
            echo "<b>New Data Type:</b> ".gettype($var)."<br><br>";

            settype($var,"boolean");
            echo "<b> After converting to boolean:<b> ".$var."<br";
            var_dump($var);
            echo "<b> Data Type:</b> ".gettype($var)."<br><br>";
                       
        ?>
    </body>
</html>