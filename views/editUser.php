
<!DOCTYPE html>
<html lang="">
<head>
    <title>Edit User data</title>
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
    <h1>Edit User data</h1>
    <div class="main-agileinfo">
        <div class="agileits-top">


            <?php
            if (isset($user)) {
            $user_data = $user;

            $user_id = $user_data['id'];
            $username = $user_data['username'];
            $email = $user_data['email'];
            $imageName = $user_data['files_id'];
            ?>
            <form action="/admin/showAllUsers/edit/update/<?= $user_id ?>" method="post" enctype="multipart/form-data">

                <input class="text" type="text" name="username" placeholder="Username"
                       value="<?php echo $username ?>" required="">

                <?php if (!empty($errors['username_length'])) { ?>
                    <p style="color: red; margin-top: 10px"><?php echo $errors['username_length']; ?></p>
                <?php } ?>

                <input class="text email" type="email" name="email" placeholder="Email" value="<?php echo $email ?>"
                       required="">
                <?php if (!empty($errors['email_format'])) { ?>
                    <p style="color: red; margin-top: -10px"><?php echo $errors['email_format']; ?></p>
                <?php } ?>

                <div class="file-input-container">
                    <label for="file-input" class="custom-file-upload">
                        Choose Picture
                    </label>
                    <input id="file-input" type="file"  name="user_image" onchange="updateUserPic(this)">
                    <span id="file-name"><?php
                        if (!empty($picName)) {
                            echo $picName;
                            echo " <a class='delete-task-button' style='margin-left: 60px' href='/admin/showAllUsers/edit/deletePic/$user_id'> Delete Image </a>";

                        }
                        ?></span>
                </div>

                <?php if (!empty($errors['invalid_file_extension'])) { ?>
                    <p style="color: red; margin-top: 10px"><?php echo $errors['invalid_file_extension']; ?></p>
                <?php } ?>

                <?php
                }
                ?>
                <input type="submit" value="UPDATE">
            </form>

            <p>Back to All Users page <a href="/admin/showAllUsers"> Go Back !</a></p>

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
