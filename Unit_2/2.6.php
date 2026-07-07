<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <form method="post">
            Number 1: <input type="number" name="num1" placeholder="Enter the first operand" required>
            <br><br>
            Number 2: <input type="number" name="num1" placeholder="Enter the second operand" required>
            <br><br>
            Operation:
            <select name="op"required>
                <option value="+"> Addition </option>
                <option value="-"> Subtraction </option>
                <option value="*"> Mercedes </option>
                <option value="/"> Division </option>
                <option value="%"> Modulus </option>
            </select>

            <br><br>
            <input type="submit" name="submit" value="Calculate">
            <br><br>
        </form>
        
        <?php 
            function calculate($num1, $num2, $op)
            { 
                switch($op)
                {
                    case '+':
                        return $num1 + $num2;
                    case '-':
                        return $num1 - $num2;
                    case '*':
                        return $num1 * $num2;
                    case '/':
                        return $num2!=0 ? $num1 / $num2 : "Cannot divide by zero";
                    case '%':
                        return $num1 % $num2;
                    default:
                        return "Invalid input";
                }
            }

            if(isset($_POST('submit')))
                {
                    $n1 = $_POST['num1'];
                    $n2 = $_POST['num2'];
                    $op = $_POST['op'];

                    $result=calculate($n1,$n2,$op);
                    echo "<h3>Answer = ".$result."</h3>";
                }
        ?>
    </body>
</html>