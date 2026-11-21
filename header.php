<head>
    <title>InterBook</title>
    <link rel="stylesheet" href="style.css">
</head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="container">
<h2>InterBook</h2>
<section>
<?php
const Books = 'Books';
const BookMarks = 'BookMarks';

/** Подвал */
function Bottom()
{
?>
</section>
2026 SG Software. Экспериментальная версия
</div>
<?php
}

/** Отладочное: вывод массива */
function out($var, $var_name = '') {
    echo '<pre style="outline: 1px dashed
        red;padding:5px;margin:10px;color:white;background:black;">';
    if (!empty($var_name)) {
        echo '<h3>'.$var_name.'</h3>';
    }
    if (is_string($var)) {
        $var=htmlspecialchars($var);
    }
    print_r($var);
    echo '</pre>';
}
?>