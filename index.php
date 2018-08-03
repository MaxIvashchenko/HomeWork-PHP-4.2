<?php 
$username = 'mivaschenko';
$password = 'neto1797';
$db = new PDO('mysql:host=localhost;dbname=mivaschenko;charset=utf8', $username, $password);

if (isset($_POST['save'])) {
    if (empty($_POST['description'])) {
        echo "Задание отсутствует!";
    } else {
        $sql_query = 'INSERT INTO tasks (description, is_done, date_added) VALUES (?, 0, now())';
        $rows = $db->prepare($sql_query);
        $rows->execute([$_POST['description']]);  
    }
}

if (isset($_GET['action'])) {
	// задача выполнена
    if ($_GET['action'] === 'done') {
        $sql_query = 'UPDATE tasks SET is_done = 1 WHERE id = ?';
        $rows = $db->prepare($sql_query);
        $rows->execute((int)[$_GET['id']]);
    }
    // редактировать задачу
    if ($_GET['action'] === 'edit') {
        $sql_query = 'SELECT * FROM tasks WHERE id = ?';
        $rows = $db->prepare($sql_query);
        $rows->execute((int)[$_GET['id']]);
        $description = $rows->fetch()['description'];
    }
    //удалить задачу
    if ($_GET['action'] === 'delete') {
        $sql_query = "DELETE FROM tasks WHERE id = ?";
        $rows = $db->prepare($sql_query);
        $rows->execute((int)[$_GET['id']]);
    }
}
 ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Занятие 4.2. Запросы SELECT, INSERT, UPDATE и DELETE</title>
</head>
<body>
<style type="text/css">
	table,th,td {
    border-collapse: collapse;
    border: 1px solid;
    padding: 5px;
   	border: 1px solid; 
   	}
   	thead {
    background-color: grey; 
    font-weight: bold;
    text-align: center; 
   	}
</style>
<h2>Список задач:</h2>
    <form method="POST">
        <?php if (isset($errors)) { ?>
        <p><?php echo $errors; ?></p>
        <?php } ?>
        <input type="text" name="description" placeholder="Описание задачи" value="<?php if (isset($description)) {echo($description);} ?> " />
        <input type="submit" name="save" value="Добавить" />
    </form>
<br>
<table>
    <tr>
        <th>Описание задачи</th>
        <th>Дата добавления</th>
        <th>Статус</th>
        <th>Редактировать статус</th>
    </tr>
    <?php
    $result = $db->query("SELECT * FROM tasks");
    while ($row = $result->fetch()) { ?>
    <tr>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo $row['date_added']; ?></td>
        <td>
            <?php  
            if ($row['is_done'] == 1) {
                echo 'Выполнено';
            } elseif ($row['is_done'] == 0) {
                echo 'В процессе';
            }
            ?>  
        </td>
        <td>
            <a href='index.php?id=<?php echo($row['id'])?>&action=edit'>Изменить</a>
            <a href='index.php?id=<?php echo($row['id'])?>&action=done'>Выполнено</a>
            <a href='index.php?id=<?php echo($row['id'])?>&action=delete'>Удалить</a>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
