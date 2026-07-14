<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            $conn = new mysqli("Localhost","root","","test");
            if($conn->connect_error)
            {
                die("onnection failed : " . $conn->connect_error);
            }

            $queries=
            [
                "SELECT CURDATE() AS CurrentDate",
                "SELECT CURTIME() AS CurrentTime",
                "SELECT UNIX_TIMESTAMP() AS UnixTime",
                "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP()) AS DateTime"
            ];
            foreach($queries as $sql)
            {
                echo"<br>";
                $result = $conn->query($sql);
                if($result)
                {
                    while($row = $result->fetch_assoc())
                    {
                        foreach($row as $key => $value)
                        {
                            echo "<b> $key :</b> $value <br>";
                        }                       
                    }
                }
            }
        ?>
    </body>
</html>