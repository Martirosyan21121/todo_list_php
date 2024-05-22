<?php

use model\TaskFile;

ob_start();

?>
<!DOCTYPE html>
<html lang="">
<head>
    <title>All tasks</title>
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
    <h1>All tasks</h1>
    <a href="/singlePage" class="add-task-button" style="margin-left: 20px">Back to your profile page</a>

    <div class="cart">
        <?php
        if (!empty($tasks)) {
            $taskFile = new TaskFile();
            foreach ($tasks as $row) {
                $text = $row['text'];
                $dataTime = $row['date_time'];
                $itemId = $row['id'];
                $createdAt = $row['created_at'];
                $file_id = $row['task_files_id'];

                $file = $taskFile->findFileById($file_id);

                switch ($row['status']) {
                    case 0:
                        $selected = '0';
                        break;
                    case 1:
                        $selected = '1';
                        break;
                    case 2:
                        $selected = '2';
                        break;
                    case 3:
                        $selected = '3';
                        break;
                    default:
                        $selected = '';
                }
                $modalId = "modal-$itemId";
                $btnId = "modal-btn-$itemId";
                ?>
                <div class='cart-item'>
                    <button id='<?php echo $btnId; ?>' class='modal-btn' value='<?php echo $itemId; ?>'
                            style='margin-left: 650px; position: absolute'>&boxH;
                    </button>
                    <div class='item-description'
                         style=' margin-left: 550px; max-width: 150px; margin-top: 60px; color: #328a02; position: absolute'>
                        From
                        - <?php echo $createdAt; ?></div>
                    <div class='item-description'
                         style='margin-left: 550px; width: 150px; margin-top: 110px; color: red; position: absolute'>
                        Until
                        - <?php echo $dataTime; ?></div>

                        <div class='item-title' style='max-width: 500px; margin: 10px'><?php echo $text; ?></div>
                        <br>
                        <div style="margin-top: 120px">
                            <a  style='margin-left: 20px;' href="/allTasks/deleteTask/<?= $itemId ?>" class='delete-task-button'>
                                Delete
                            </a>

                            <a  style='margin-left: 40px;'  href="/allTasks/update/<?= $itemId ?>" class='add-task-button'>
                                Update
                            </a>

                            <?php
                            if ($file !== null) {
                                $fileName = $file['files_name'];
                                if ($fileName !== null) {
                                    $downloadPath =  '/../img/taskFiles/' . $fileName;
                                    ?>
                                    <a href="<?php echo $downloadPath ?>" download style='margin-left: 70px;'
                                       class='download-file-button'>Keep file
                                    </a>
                                <?php } else {
                                    echo "<a class='download-file-button' style='margin-left: 70px' onclick='fileNotFound()'>Keep file </a>";
                                }
                            } else {
                                echo "<a class='download-file-button' onclick='fileNotFound()' style='margin-left: 70px'>Keep file </a>";
                            }
                            ?>
                            <form action='/allTasks/status/<?= $itemId ?>' method='post'>
                                <select id='statusSelect' class='custom-select' name='status'
                                        style='margin-left: 485px; margin-bottom: 15px; color: #007bff'>
                                    <option value='0' <?php echo ($selected == '0') ? 'selected' : ''; ?>>Not Started
                                    </option>
                                    <option value='1' <?php echo ($selected == '1') ? 'selected' : ''; ?>>In Process
                                    </option>
                                    <option value='2' <?php echo ($selected == '2') ? 'selected' : ''; ?>>In Test
                                    </option>
                                    <option value='3' <?php echo ($selected == '3') ? 'selected' : ''; ?>>Done
                                    </option>
                                </select>
                            </form>

                        </div>
                    <div id='<?php echo $modalId; ?>' class='modal'>
                        <div class='modal-content'>
                            <span class='close'>&times;</span>
                            <?php
                            $status = '';
                            if ($selected == '0') {
                                $status = 'Not Started';
                            } else if ($selected == '1') {
                                $status = 'In Process';
                            } else if ($selected == '2') {
                                $status = 'In Test';
                            } else if ($selected == '3') {
                                $status = 'Done';
                            }
                            ?>
                            <p style='margin: 10px'>ID - <?php echo $itemId; ?></p>
                            <p style='margin: 10px; max-width: 90%; word-wrap: break-word; '>Subject
                                - <?php echo $text; ?></p>
                            <p style='margin: 10px'>Status - <?php echo $status; ?></p>
                            <p style='color: #328a02; margin: 10px'>Last Updated - <?php echo $createdAt; ?></p>
                            <p style='color: red; margin: 10px'>Deadline - <?php echo $dataTime; ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>You don't have any data !!!</p>";
        }
        ?>
    </div>
    <br>

    <div class="container">
        <?php
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            echo "<a class='add-task-button' style='margin-left: 50px;' href='/allTasks/addTask/$userId'>Add tasks</a>";
        }
        ?>
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

<script src="../js/script.js"></script>
<script src="../js/taskHistory.js"></script>

<script>
    function fileNotFound() {
        alert("Task has on file");
    }
</script>
</body>
</html>
