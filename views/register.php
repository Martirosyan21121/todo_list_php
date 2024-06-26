
<!DOCTYPE html>
<html lang="">
<head>
    <title>Register page</title>
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
    <h1>Please register</h1>
    <div class="main-agileinfo">
        <div class="agileits-top">
            <form action="/register/user" method="post">

                <input class="text" type="text" name="username" placeholder="Username" required="">
                <?php if (!empty($errors['username_length'])) { ?>
                    <p style="color: red; margin-top: 10px"><?php echo $errors['username_length']; ?></p>
                <?php } ?>

                <input class="text email" type="email" name="email" placeholder="Email" required="">
                <?php if (!empty($errors['email_format'])) { ?>
                    <p style="color: red; margin-top: -10px"><?php echo $errors['email_format']; ?></p>
                <?php } else if (!empty($errors['email_exists'])){ ?>
                <p style="color: red; margin-top: -10px"><?php echo $errors['email_exists']; ?></p>
                <?php }?>

                <input class="text" type="password" name="password" placeholder="Password" required="" style="margin-top: 10px">
                <?php if (!empty($errors['password_length'])) { ?>
                    <p style="color: red; margin-top: 10px"><?php echo $errors['password_length']; ?></p>
                <?php } ?>

                <br>
                <input type="submit" value="REGISTER">
            </form>

            <p>You have an Account? <a href="/"> Login Now!</a></p>

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