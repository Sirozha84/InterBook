<form action="editor.php" method="post" enctype="multipart/form-data">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<?php
require('header.php');
session_start();

// Обработка кнопок
foreach ($_POST as $button  => $vl) {
    if ($button == 'save') {
        file_put_contents(Books.'/'.$_SESSION['book']['title'].'.json', json_encode($_SESSION['book'], JSON_UNESCAPED_UNICODE));
        header('Location: index.php');
    }
    if ($button == 'close') header('Location: index.php');
}

// Обработка параметров
if (isset($_GET['book'])) {
    $filename = $_GET['book'];
    $_SESSION['filename'] = $filename;
    $_SESSION['book'] = json_decode(file_get_contents(Books.'/'.$filename), true);
    $_SESSION['page'] = '0';
}
if (isset($_GET['page'])) $_SESSION['page'] = $_GET['page'];
$book = $_SESSION['book'];
$page = $_SESSION['page'];
$chapter = [];

// Рисование страницы
echo '<button name="save">Сохранить</button>';
echo '<button name="close">Закрыть</button>';
echo '<hr>';
echo "Название: {$book['title']}<br>Автор: {$book['author']}<br><br>";
// Тут поля буду редактируемые, и учесть, что при переименовании книги, нужно переименовать файл
// Для названия файла нужно сделать функцию, которая будет делать безопасное имя файла
// А ещё нужно учесть, что файл с таким именем уже может существовать, тогда к имени нужно добавить цифру

echo '<div class="layout"><aside class="sidebar">Содержание<br><br>';
DrawThree($book['chapter']);    
echo '</aside><main>';
DrawEditor();
echo '</main></div>';


Bottom();

function DrawThree($chap, $forkn = 0, $threen = 0, $bookmarks = [], $lasts = []) {
    global $filename;
    global $page;
    global $chapter;

    $lp = '0';
    foreach($bookmarks as $bm) $lp .= '-'.$bm;
    $bl = $page == $lp ? '<b>' : '';
    $br = $page == $lp ? '</b>' : '';
    if ($page == $lp) $chapter = $chap;


    // Ветка первой строки
    echo '<code>';
    $c = 0;
    foreach($lasts as $last)
        if (++$c < count($lasts)) echo $last? "&nbsp&nbsp":"│&nbsp";
        else echo $last? "└─":"├─";
    echo '</code>';
    
    // Название ветки
    $name = 'Начало';
    if (isset($chap['fork'])) {
        if ($chap['fork'] != '') $name = $chap['fork'];
        else $name = "(Ветка $forkn)";
    }
    echo "<a href=\"/editor.php?book=$filename&page=$lp\">$bl$name$br</a><br>";

    // Ветка второй строки
    echo '<code>';
    $c = 0;
    foreach($lasts as $last)
        if (++$c < count($lasts)) echo $last? "&nbsp&nbsp":"│&nbsp";
        else echo $last? "&nbsp&nbsp":"│&nbsp";
    echo '</code>';

    // Заголовок
    if ($chap['title'] != '') echo $bl.'['.$chap['title'].']'.$br.'<br>';
    elseif ($chap['text'] != '') echo $bl.mb_substr($chap['text'], 0, 10)."...$br<br>";
    else echo "$bl(пусто)$br<br>";
    
    // Внутренние подветки
    if (isset($chap['forks'])) {
        $n = 0;
        foreach($chap['forks'] as $ch) {
            $bookmarks[$threen] = $n++;
            $lasts[$threen] = ($n == count($chap['forks']));
            DrawThree ($ch, $n, $threen + 1, $bookmarks, $lasts);
        }
    }
}

function DrawEditor() {
    global $chapter;

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
}