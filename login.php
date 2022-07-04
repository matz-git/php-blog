<?php require "check.php"; ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Blog</title>
    <link href="style.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
</head>

<body>
    <div class="flex-container">
        <div class="row">
            <div class="flex-item">
                <form id="login-form" method="post" target="_self">
                    <h1>LOGIN</h1>

                    <input type="hidden" value="user" type="text" name="user" required />
                    <input type="password" name="password" required />

                    <?php if (isset($failed)) { ?>
                        <div id="bad-login">Invalid password.</div>
                    <?php } ?>

                    <input type="submit" value="login" />
                </form>
            </div>
        </div>
    </div>
</body>

</html>