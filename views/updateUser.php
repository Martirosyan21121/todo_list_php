<?php
session_start();

$email_exist = '';
if (isset($_GET['error']) && $_GET['error'] === 'invalid_email') {
    $email_exist = 'Invalid email.';
}

$username_length = '';
if (isset($_GET['error']) && $_GET['error'] === 'min_length') {
    $username_length = "Username minimum length must be at least 5 characters.";
}
$invalid_extension = '';
if (isset($_GET['error']) && $_GET['error'] === 'invalid_file_extension') {
    $invalid_extension = "Invalid file extension. Please upload a JPG, JPEG or PNG file.";
}
?>

<!DOCTYPE html>
<html lang="">
<head>
    <title>Update your data</title>
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
    <h1>Update your data</h1>
    <div class="main-agileinfo">
        <div class="agileits-top">

            <form action="../user/user_update_logic.php" method="post" enctype="multipart/form-data">
                <?php
                if (isset($_SESSION['user_data'])) {
                    $user_data = $_SESSION['user_data'];

                    $user_id = $user_data['id'];
                    $username = $user_data['username'];
                    $email = $user_data['email'];
                    $imageName = $user_data['files_id'];
                    ?>
                    <input class="text" type="text" name="username" placeholder="Username"
                           value="<?php echo $username ?>" required="">

                    <?php if (!empty($username_length)) { ?>
                        <p style="color: red;"><?php echo $username_length; ?></p>
                    <?php } ?>

                    <input class="text email" type="email" name="email" placeholder="Email" value="<?php echo $email ?>"
                           required="">

                    <?php if (!empty($email_exist)) { ?>
                        <p style="color: red;"><?php echo $email_exist; ?></p>
                    <?php } ?>

                    <div class="file-input-container">
                        <label for="file-input" class="custom-file-upload">
                            Choose Picture
                        </label>
                        <input id="file-input" type="file"  name="user_image" onchange="updateUserPic(this)">
                        <span id="file-name"></span>
                    </div>

                    <?php if (!empty($invalid_extension)) { ?>
                        <p style="color: red;"><?php echo $invalid_extension; ?></p>
                    <?php } ?>


                    <input class="text" type="hidden" name="id" value="<?php echo $user_id ?>">
                    <?php
                }
                ?>
                <input type="submit" value="UPDATE">
            </form>

            <p>Back to your page <a href="/singlePage.php"> Go Back !</a></p>

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
<script>
    function updateUserPic(input) {
        let fileName = '';
        if (input.files.length > 0) {
            fileName = input.files[0].name;
        }
        let fileNameSpan = document.getElementById('file-name');
        fileNameSpan.textContent = fileName;
    }
</script>
</body>
</html>