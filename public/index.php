<?php 
require_once __DIR__ . '/../src/library/ExtractImages.php';

use library\ExtractImages as ExtractImages;

// задаем пути к каталогам файла и выгрузки изображений
$input_dir = realpath(__DIR__) . '/input/';
$output_dir = realpath(__DIR__) . '/output/';
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
            
            <header>
                <h1><a href="/prodamus-pdf-powerpoint-extract-library/">PDF/PowerPoint extract library</a></h1>
            </header>

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

                    // получаем файл из $_FILES
                    $file = array_values($_FILES)[0];

                    // сохраняем файл
                    ExtractImages::saveFile($file, $input_dir );
                    
                    // разбираем файл на картинки в указанном в последнем аргументе формате
                    ExtractImages::extractImagesFromFile($file, $input_dir, $output_dir, 'png' );
                } else {
                    echo "<p>Выберите файл и нажмите 'Запустить обработку'</p>";
                }

            ?>
            </div>
        </div>
        <?php 
            $slides = ExtractImages::getCatalogContent($output_dir);

            if(!empty($slides)): ?>
            
            <div class="row">
                <div class="slide-list">
                    <h4>Загружено слайдов: <?= count($slides) ?></h4>
                    <?php foreach($slides as $key => $slide): ?>
                        
                        <figure>
                            <img id="<?= $slide ?>" src="<?= $_SERVER['REQUEST_URI'] . 'output/' . $slide ?>" alt="<?= explode('.', $slide)[0] ?>" />
                            <figcaption>Слайд: <?= explode('.', $slide)[0] ?></figcaption>
                        </figure>
                            
                    <?php endforeach; ?>
                </div>
                <div class="slide-view">
                    <figure>
                        <img id="slide-view" src="" alt="" />
                        <figcaption><b>Слайд не выбран</b></figcaption>
                    </figure>
                </div>
            </div>

            <?php 
            endif;
        ?>
        

    </div>
    <script src="js/scripts.js"></script>
</body>
</html>