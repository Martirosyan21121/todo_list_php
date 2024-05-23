<!DOCTYPE html>
<html lang="">
<head>
    <title>All Users</title>
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
    <h1>All Active Users</h1>

    <nav class="top-bar">
        <?php
        if (isset($_SESSION['user'])) {
            $adminId = $_SESSION['user']['id'];
            echo "<a class='add-task-button' style='margin-left: 20px;' href='/adminPage/$adminId'>Back</a>";
        }
        ?>

        <a class='deactivate-button' style='margin-left: 80%;' href='/admin/showAllUsers/allDeactivates'>Deactivate Users</a>

    </nav>

    <table>
        <thead>
        <tr>
            <th>ID
            <th>Username
            <th>Email
            <th>All tasks
            <th>Edit user
            <th>Deactivate
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
                <button class="add-task-button"> All Tasks</button>
            <td>
                <button class="download-file-button"> Edit</button>

            <td>
                <form action="/admin/showAllUsers/deactivate/<?= $user['id'] ?>" method="get">
                    <button class="deactivate-button"> Deactivate</button>
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
