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

function DrawThree($chapter, $forkn = 0, $threen = 0, $lasts = []) {
    // Ветка первой строки
    echo '<code>';
    $c = 0;
    foreach($lasts as $last)
        if (++$c < count($lasts)) echo $last? "&nbsp&nbsp":"│&nbsp";
        else echo $last? "└─":"├─";
    echo '</code>';
    
    // Заголовок
    if ($chapter['title'] != '')
        echo $chapter['title'].'<br>';
    else
        echo "Вилка $forkn<br>";

    // Ветка второй строки
    echo '<code>';
    $c = 0;
    foreach($lasts as $last)
        if (++$c < count($lasts)) echo $last? "&nbsp&nbsp":"│&nbsp";
        else echo $last? "&nbsp&nbsp":"│&nbsp";
    echo '</code>';

    // Текст
    echo $chapter['text'].'<br>';
    
    // Внутренние подветки
    if (isset($chapter['forks'])) {
        $n = 0;
        foreach($chapter['forks'] as $ch) {
            $lasts[$threen] = (++$n == count($chapter['forks']));
            DrawThree ($ch, $n, $threen + 1, $lasts);
        }
    }
}