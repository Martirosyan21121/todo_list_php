
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
            <form action="/register/user" method="post" enctype="multipart/form-data">
                <input class="text" type="text" name="username" placeholder="Username" required="">
                <?php if (!empty($username_length)) { ?>
                    <p style="color: red;"><?php echo $username_length; ?></p>
                <?php } ?>
                <input class="text email" type="email" name="email" placeholder="Email" required="">
                <?php if (!empty($email_exist)) { ?>
                    <p style="color: red;"><?php echo $email_exist; ?></p>
                <?php } ?>
                <input class="text" type="password" name="password" placeholder="Password" required="">
                <?php if (!empty($password_p)) { ?>
                    <p style="color: red;"><?php echo $password_p; ?></p>
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