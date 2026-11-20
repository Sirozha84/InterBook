<form action="index.php" method="post" enctype="multipart/form-data">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<?php
require('header.php');
session_start();
foreach ($_POST as $button  => $vl) {
    if ($button == 'newbook') {
        $book = ['title' => 'Новая книга', 'author' => 'Автор', 'chapter' => ['title' => 'Новая глава', 'text' => '']];
        file_put_contents(Books.'/Новая книга.json', json_encode($book, JSON_UNESCAPED_UNICODE));
        header('Location: editor.php?book=Новая книга.json');
    }
    if (str_starts_with($button, 'edit>')) {
        $file = $_SESSION['files'][str_replace('edit>', '', $button)];
        header("Location: editor.php?book=$file");
    }
}
Catalog();
Bottom();

function Catalog() {
    if (!file_exists(Books)) mkdir(Books);
    $files = scandir(Books);
    if (count($files) == 2) echo 'Нет доступных книг'; else echo 'Доступные книги';
    echo '<br><br><table>';
    $n = 1;
    $_SESSION['files'] = [];
    foreach ($files as $file) {
        if (!str_starts_with($file, '.') && str_ends_with($file, '.json')) {
            $title = str_replace('.json', '', $file);
            $_SESSION['files'][$n] = $file;
            echo "<tr><td>$title</td><td><button name=\"edit>$n\" class=\"smallbutton\">Ред.</button></td></tr>";
            $n++;
        }
    }
    echo '</table><hr>';
    echo '<button name="newbook">Создать новую</button>';
}
?>