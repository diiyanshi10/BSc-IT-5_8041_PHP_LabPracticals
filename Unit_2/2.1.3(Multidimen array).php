<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <form>
            <input type="text" name="noOfBox" placeholder="Enter the no.of brands you want to add.">
            <input type="submit" name="Generate" value="Generate">
        </form>
            <?php
                $noBox=$_GET['noOfBox'];
                echo'<form method="GET"><table>';
                    echo'<tr>';
                        echo'<th>Brand</th>';
                        echo'<th>model</th>';
                        echo'<th>Price</th>';
                    echo'</tr>';
                for($i=1;$i<=$noBox;$i++)
                {
                        echo'<tr>';
                            echo '<td><input type="text" name="brand['.$i.'][1]" placeholder="Enter Brand"></td>';
                            echo '<td><input type="text" name="brand['.$i.'][2]" placeholder="Enter Model"></td>';
                            echo '<td><input type="text" name="brand['.$i.'][3]" placeholder="Enter Price"></td>';
                        echo'</tr>';
                }
                echo'</table>';
                echo'<input type="submit" name="Submit">';
                echo'</form>';
                $brand=$_GET['brand'];
                for($i=1;$i<=count($brand);$i++)
                {
                    for($j=1;$j<=3;$j++)
                    {
                        echo $brand[$i][$j]." - ";
                    }
                    echo "<br>";
                }
            ?>
    </body>
</html>