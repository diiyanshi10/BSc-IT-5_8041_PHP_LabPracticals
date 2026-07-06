<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            $str="Welcome to PHP";
            echo "<h2>PHP string functions</h2>";

            //3-str_word_count()
            echo "<h3>1. str_word_count()</h3>";
            echo "Number of words = ".str_word_count($str);

        ?>
    </body>
</html>