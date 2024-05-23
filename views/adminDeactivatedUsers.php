
<!DOCTYPE html>
<html lang="">
<head>
    <title>Single page</title>
    <link rel="icon" href="/img/logo/logo.jpg" type="image/gif" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

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
    <h1>All deactivated Users</h1>

    <nav class="top-bar">
            <a class='add-task-button' style='margin-left: 20px;' href='/admin/showAllUsers'>Back</a>
    </nav>

    <table>
        <thead>
        <tr>
            <th>ID
            <th>Username
            <th>Email
            <th>Activate User
            <th>Delete User
        </thead>
        <tbody>
        <?php
        if (!empty($allUsers)){
        foreach ($allUsers as $user){

        ?>

        <tr>
            <td> <?= $user['id'] ?>
            <td> <?= $user['username'] ?>
            <td> <?= $user['email'] ?>
            <td>
                <form>
                    <button class="add-task-button"> Activate</button>
                </form>
            <td>
                <form action="/admin/showAllUsers/delete/<?= $user['id'] ?>" method="get">
                    <button class="delete-task-button"> Delete</button>
                </form>
                <?php }
                } else {
                    echo '<h1> Users not found </h1>';
                    echo '<br>';
                } ?>
        </tbody>
    </table>
    <br>

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

