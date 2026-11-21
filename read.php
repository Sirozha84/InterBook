<form action="read.php" method="post" enctype="multipart/form-data">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<?php
require('header.php');
session_start();

// Обработка кнопок
foreach ($_POST as $button  => $vl) {
    if ($button == 'close') header('Location: index.php');
    if (str_starts_with($button, 'fork>')) $_SESSION['bookmark'][] = str_replace('fork>', '', $button);
}

// Обработка параметров
if (isset($_GET['book'])) {
    $filename = $_GET['book'];
    $_SESSION['book'] = json_decode(file_get_contents(Books.'/'.$filename), true);
    $_SESSION['bookmark'] = [];
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
echo '<button name="close">Закрыть</button>';
echo "Страница: $page<hr>";

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