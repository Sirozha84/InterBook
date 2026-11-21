<form action="read.php" method="post" enctype="multipart/form-data">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<?php
require('header.php');
session_start();

// Обработка кнопок
foreach ($_POST as $button  => $vl) {
    if ($button == 'close') {
        if (!file_exists(BookMarks)) mkdir(BookMarks);
        file_put_contents(BookMarks.'/'.$_SESSION['filename'], json_encode($_SESSION['bookmark']));
        header('Location: index.php');
    }
    if ($button =='back') array_pop($_SESSION['bookmark']);
    if (str_starts_with($button, 'fork>')) $_SESSION['bookmark'][] = str_replace('fork>', '', $button);
}

// Обработка параметров
if (isset($_GET['book'])) {
    $filename = $_GET['book'];
    $_SESSION['filename'] = $filename;
    $_SESSION['book'] = json_decode(file_get_contents(Books.'/'.$filename), true);
    if (file_exists(BookMarks.'/'.$filename))
        $_SESSION['bookmark'] = json_decode(file_get_contents(BookMarks.'/'.$filename), true);
    else $_SESSION['bookmark'] = [];
}
$book = $_SESSION['book'];
$bookmark = $_SESSION['bookmark'];

// Поиск главы
$page = '1';
$chapter = $book['chapter'];
foreach($bookmark as $f) {
     $chapter = $chapter['forks'][$f];
     $page .= '>'.$f + 1;
}

// Рисование страницы
if (count($bookmark) > 0) echo '<button name="back">Назад</button>';
echo '<button name="close">Закрыть</button>';
echo "{$book['title']}, {$book['author']}, Страница: $page<hr>";

// Рисование главы
if ($chapter['title'] != '') echo "<h1>{$chapter['title']}</h1>";;
if ($chapter['text'] != '') echo $chapter['text'];
else echo '(пусто)';
echo '<hr>';

// Кнопки веток
if (isset($chapter['forks'])) {
    $n = 0;
    foreach ($chapter['forks'] as $fork) {
        $caption = $fork['fork'] != '' ? $fork['fork'] : 'Ветка '.$n + 1;
        echo "<button name=\"fork>$n\">$caption</button>";
        $n++;
    }
} else echo '<h1>Конец</h1>';

Bottom();
?>