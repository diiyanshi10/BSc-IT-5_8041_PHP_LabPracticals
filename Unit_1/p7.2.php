<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Program 7.2</title>
        <link rel="stylesheet" href="Header.css">
        <link rel="stylesheet" href="Footer.css">
    </head>
    <body>
        <?php require "header.php"; ?>

        <main class="main">
            <h2>Main Body</h2>
            <p>
                This is the main body of the page. The header and footer are added
                using the PHP require function.
            </p>

            <div class="info-box">
                <?php
                    echo "This message is printed using PHP.";
                ?>
            </div>
        </main>

        <?php require "footer.php"; ?>
    </body>
</html>
