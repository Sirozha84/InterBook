<form action="editor.php" method="post" enctype="multipart/form-data">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<?php
require('header.php');
session_start();
foreach ($_POST as $button  => $vl) {
    if ($button == 'save') {
        file_put_contents(Books.'/'.$_SESSION['book']['title'].'.json', json_encode($_SESSION['book'], JSON_UNESCAPED_UNICODE));
        header('Location: index.php');
    }
    if ($button == 'close') header('Location: index.php');
}
if (isset($_GET['book']))
    $_SESSION['book'] = json_decode(file_get_contents(Books.'/'.$_GET['book']), true);
$book = $_SESSION['book'];
echo '<button name="save">Сохранить</button>';
echo '<button name="close">Закрыть</button>';
echo "<br><br>Название: {$book['title']}<br>Автор: {$book['author']}<br>";
// Тут поля буду редактируемые, и учесть, что при переименовании книги, нужно переименовать файл
// Для названия файла нужно сделать функцию, которая будет делать безопасное имя файла
// А ещё нужно учесть, что файл с таким именем уже может существовать, тогда к имени нужно добавить цифру
DrawThree();

Bottom();

function DrawThree() {
    echo 'Тут будет рисоваться дерево заголовков';
}