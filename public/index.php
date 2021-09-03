<?php 
require_once __DIR__ . '/../src/library/ExtractImages.php';

use library\ExtractImages as ExtractImages;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>PDF/PowerPoint extract library</title>
</head>
<body>
    <div class="wrapper">
        <div class="col">
            
            <h1><a href="/prodamus-pdf-powerpoint-extract-library/">PDF/PowerPoint extract library</a></h1>
            
            <form id="form" action="" method="post" enctype="multipart/form-data">
                <fieldset>
                    <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
                    <input type="file" name="input-upload" />              
                    <button type="submit" id="btn-extract" name="btn-extract">Запустить обработку</button>
                </fieldset>
            </form>
            
            <div class="message-box">
            <?php

                if( isset( $_POST['btn-extract'] ) ) { 
                    
                    // задаем пути к каталогам файла и выгрузки изображений
                    $input_dir = realpath(__DIR__) . '/input/';
                    $output_dir = realpath(__DIR__) . '/output/';

                    // получаем файл из $_FILES
                    $file = array_values($_FILES)[0];

                    // сохраняем файл
                    ExtractImages::saveFile($file, $input_dir );
                    
                    // разбираем файл на картинки
                    ExtractImages::extractImagesFromFile($file, $input_dir, $output_dir );

                } else {
                    echo "<p>Выберите файл и нажмите 'Запустить обработку'</p>";
                }

            ?>
            </div>
        </div>
        
    </div>

</body>
</html>