<form action="index.php" method="post" enctype="multipart/form-data">
<head>
    <title>Заголовок страницы</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
require('header.php');
foreach ($_POST as $button  => $vl) {
    if ($button == 'newbook') echo "Заглушка: создание новой книги";
    if (str_starts_with($button, 'edit')) echo "Заглушка: Редактирование ". str_replace('edit>', '', $button);
}
//out($_POST);
Catalog();

function Catalog() {
    if (!file_exists(Books)) mkdir(Books);
    $files = scandir(Books);
    //out($files);
    
    if (count($files) == 2) echo 'Нет доступных книг'; else echo 'Доступные книги';
    echo '<table>';
    foreach ($files as $file) {
        if (!str_starts_with($file, '.')) {
            $title = str_replace('.json', '', $file);
            echo "<tr><td>$title</td><td><button name=\"edit>$file\" class=\"smallbutton\">Редактировать</button></td></tr>";
        }
    }
    echo '</table><hr>';
    echo '<button name="newbook">Создать новую</button>';
}
?>