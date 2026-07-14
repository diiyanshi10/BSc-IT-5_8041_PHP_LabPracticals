<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            $conn = new mysqli("localhost", "root","", "test");
            if ($conn->connect_error){
                die("connection failed : " . $conn->connect_error);
            }
            $queries = [
                "SELECT LENGTH('Hello PHP') AS Result",
                "SELECT CONCAT('Hello',' ','PHP') AS Result",
                "SELECT CONCAT_WS(' - ','PHP','MySQL','HTML') AS Result",
                "SELECT TRIM('  Hello PHP   ') AS Trimmed,
                        LTRIM('   Hello PHP') AS LTrimmed,
                        RTRIM('Hello PHP   ') AS RTrimmed",
                "SELECT LPAD('PHP',8,'*') AS LPad,
                        RPAD('PHP',8,'*') AS RPad,
                        LOCATE('SQL','MySQL Database') AS LOCATE",
                "SELECT SUBSTR('Hello PHP', 1, 5) AS SubString",
                "SELECT LCASE('Hello PHP') AS LowerCase,
                        UCASE('Hello PHP') AS Uppercase",
                "SELECT REPEAT('PHP' ,3) AS RepeatString,
                        REPLACE('I Like Java','Java','PHP')"
            ];

            foreach($queries as $sql){
                echo "<br>";
                $result = $conn->query($sql);
                if($result){
                    while($row = $result->fetch_assoc()){
                        foreach($row as $key => $value){
                            echo"<b>$key :</b> $value <br>";
                        }
                    }
                }
                else{
                    echo "Query Error";
                }
            }
        ?>
    </body>
</html>