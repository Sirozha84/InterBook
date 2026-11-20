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
if (isset($_GET['book'])) {
    $_SESSION['book'] = json_decode(file_get_contents(Books.'/'.$_GET['book']), true);
    $_SESSION['mainchapter'] = [];
}
$book = $_SESSION['book'];
echo '<button name="save">Сохранить</button>';
echo '<button name="close">Закрыть</button>';
echo "<br><br>Название: {$book['title']}<br>Автор: {$book['author']}<br><br>";
// Тут поля буду редактируемые, и учесть, что при переименовании книги, нужно переименовать файл
// Для названия файла нужно сделать функцию, которая будет делать безопасное имя файла
// А ещё нужно учесть, что файл с таким именем уже может существовать, тогда к имени нужно добавить цифру
echo 'Содержание<br><br>';
DrawThree($book['chapter']);

Bottom();

//function DrawThree($chapter, $forkn = 0, $threen = 0, $ln = 0) {
function DrawThree($chapter, $forkn = 0, $threen = 0, $last = false) {
    //echo $last;
    if ($threen != 0) { echo '<code>'; for ($i = 1; $i < $threen; $i++) echo '│&nbsp'; echo $last ? '└─' : '├─'; echo '</code>';}
    if ($chapter['title'] != '')
        echo $chapter['title'].'<br>';
    else
        echo "Вилка $forkn<br>";
    if ($threen != 0) { echo '<code>'; for ($i = 1; $i < $threen; $i++) echo '│&nbsp'; echo $last ? '&nbsp&nbsp' : '│&nbsp'; echo '</code>'; }
    echo $chapter['text'].'<br>';
    if (isset($chapter['forks'])) {
        $n = 0;
        foreach($chapter['forks'] as $ch) {
            $last = $n == count($chapter['forks']);
            DrawThree ($ch, ++$n, $threen + 1, $n == count($chapter['forks']));
        }
    }
}