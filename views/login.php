

<!DOCTYPE html>
<html lang="">
<head>
    <title>Login page</title>
    <link rel="icon" href="/img/logo/logo.jpg" type="image/gif" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        } </script>

    <link href="../css/style.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
</head>
<body>
<div class="main-w3layouts wrapper">
    <h1>Please Log in</h1>

    <div class="main-agileinfo">
        <div class="agileits-top">
            <form action="/login" method="post">
                <input class="text email" type="email" name="email" placeholder="Email" required="">
                <?php if (!empty($errors['email_format'])) { ?>
                    <p style="color: red; margin-top: -10px"><?php echo $errors['email_format']; ?></p>
                <?php } elseif (!empty($errors['email_not_exists'])){ ?>
                <p style="color: red; margin-top: -10px"><?php echo $errors['email_not_exists']; ?></p>
                <?php } ?>

                <input class="text" type="password" name="password" placeholder="Password" required="">
                <?php
                if (!empty($errors['login_failed'])) { ?>
                <p style="color: red; margin-top: 10px"><?php echo $errors['login_failed']; ?></p>
                <?php } ?>

                <input type="submit" value="LOGIN">
            </form>

            <p>Don't have an Account? <a href="/register"> Register Now!</a></p>
        </div>
    </div>

    <div class="colorlibcopy-agile">
        <p>© 2024 project ToDo list using PHP</p>
    </div>
    <ul class="colorlib-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
</body>

</html>