<?php 
namespace library;

require_once __DIR__ . '/helpers/ImagickHelper.php';
require_once __DIR__ . '/helpers/ErrorHelper.php';
require_once __DIR__ . '/helpers/EnvHelper.php';

use library\helpers\ImagickHelper as ImagickHelper;
use library\helpers\ErrorHelper as ErrorHelper;
use library\helpers\EnvHelper as EnvHelper;

/**
 * Класс для извлечения картинок из файла.
 *
 * @package     prodamus
 * @copyright   2021 Prodamus Ltd. http://prodamus.ru/
 * @author      Mihail Nesterov <mhause@mail.ru>
 * @version     1.0
 * @since       30.08.2021
 */
class ExtractImages
{
    /**
     * PHP-функция, используемая для запуска внешних команд.
     * 
     * @var string
     */
    private static $func_name = 'exec';

    /**
     * Команда, используемая для преобразования в pdf-файл.
     * 
     * @var string
     */
    private static $command_to_pdf = 'unoconv';
    
    /**
     * Deprecated!!!
     * Команда, используемая для извлечения изображений из pdf-файла.
     * 
     * @var string
     */
    //private static $command_from_pdf = 'pdfimages';


    /**
     *  MIME, допустимые для обработки.
     * 
     * @var array
     */
    private static $available_mime_types = array(
        'pdf' => 'application/pdf',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    );

    /**
     * MIME, доступные для преобразования в PDF.
     * 
     * @var array
     */
    private static $converted_mime_types = array('ppt', 'pptx');

    /**
	 * Извлечь изображения из файла.
     * 
     * @param array $file
     * @param string $input_dir
     * @param string $output_dir
	 */
    public static function extractImagesFromFile(array $file, $input_dir, $output_dir, $image_format = 'png') {
        try {
            if( true === EnvHelper::isFunctionEnabled(self::$func_name) ) {

                $path_to_file_upload = self::sanitizePath($input_dir) . $file['name'];
                $path_to_pdf = pathinfo($path_to_file_upload)['dirname'] . '/' .  pathinfo($path_to_file_upload)['filename'] . '.pdf';
                
                $convert_command = self::buildConvertToPdfCommand($path_to_file_upload);
                
                if( isset($convert_command) ) {                   
                    exec( $convert_command );
                }
 
                self::clearDirectory($output_dir);
                self::convertPdfToImages($path_to_pdf, $output_dir, $image_format);
                self::getCatalogContent($output_dir);
    
            }
        } catch (Exception $e) {
            echo 'Extract images exeption: ',  $e->getMessage(), "\n";
        }
    }

    /**
	 * Получить изображения из PDF-файла с помощью Imagick
	 *
     * @param array $file
     * @param string $output_dir
     * @param string $image_format
     * @return array
	 */
    public static function convertPdfToImages($pdf_file, $output_dir, $image_format = 'png') {
        
        ImagickHelper::isImagickLoaded();
        ImagickHelper::isFileFormatEnabled( $image_format );

        $result = array();
        $imagick = new \Imagick();
        $imagick->readImage($pdf_file);
        
        foreach ( $imagick as $key => $image ) {
            
            $now = date("d-m-Y_H-i-s");
            $image->setImageFormat( $image_format );
            $image_file = self::sanitizePath( $output_dir ) . ((int)$key+1) . '.' . $image_format;
            
            if( true === $image->writeImage($image_file) ) {
                $result[] = $image_file;
            }
        }
        
        return $result;
    }

    /**
	 * Создать команду преобразования в PDF-файл.
	 *
     * @param string $path_to_file
     * @return string
	 */
    private static function buildConvertToPdfCommand($path_to_file) {
        try {
            
            $file_ext = end(explode('.', $path_to_file));
            $command = self::$command_to_pdf;
            
            if( in_array($file_ext, self::$converted_mime_types ) ) {
                if( true === EnvHelper::isCommandEnabled($command) ) {
                    return $command . " -f pdf '" . $path_to_file . "'";
                }
            }  

            return false;

        } catch (Exception $e) {
            echo 'Convert to PDF command exeption: ',  $e->getMessage(), "\n";
        }
    }

    /**
	 * Deprecated!
     * Создать команду извлечения изображений из PDF-файла.
	 *
     * @param string $path_to_file
     * @param string $output_dir
     * @return string
	 */
    /*private static function buildExtractCommand($path_to_file, $output_dir) {
        try {
            
            $file_ext = end(explode('.', $path_to_file));
            $command = self::$command_from_pdf;
            
            if( !in_array($file_ext, self::$converted_mime_types ) ) {
                if( true === EnvHelper::isCommandEnabled($command) ) {   
                    return $command . " -j " . $path_to_file . " " . $output_dir . "image -png";
                }
            }
            
            return false;

        } catch (Exception $e) {
            echo 'Build extract command exeption: ',  $e->getMessage(), "\n";
        }
    }*/

    /**
	 * Сохранить файл в каталог $input_dir.
     * 
     * @param string $uploaded_file
     * @param string $input_dir
	 */
    public static function saveFile($uploaded_file, $input_dir) {
        try {
            
            ErrorHelper::getFileUploadError($uploaded_file['error']);
            
            ErrorHelper::getMimeTypesErrors($uploaded_file, self::$available_mime_types);
            
            move_uploaded_file($uploaded_file['tmp_name'], $input_dir . $uploaded_file['name']);

        } catch (Exception $e) {
            echo 'File save exeption: ',  $e->getMessage(), "\n";
        }
    }

    /**
	 * Очистить каталог.
     * 
     * @param string $dir
	 */
    private static function clearDirectory($dir) {
        try {
            
            $path = self::sanitizePath($dir);
            array_map( 'unlink', array_filter((array) glob("$path*") ) );

        } catch (Exception $e) {
            echo 'Clear directory exeption: ',  $e->getMessage(), "\n";
        }
    }

    /**
	 * Обработать путь к каталогу, добавить "/" в конец пути если его нет.
     * 
     * @param string $path
     * @return string
	 */
    private static function sanitizePath( $path ) {
        
        $last_char = substr($path, -1);

        if( substr($path, -1) !== '/' ) {
            return "$path/";
        }

        return $path;
    }

    /**
	 * Вывести содержимое каталога.
     * 
     * @param string $output_dir
     * @return array
	 */
    public static function getCatalogContent($output_dir) {

        $files = array_slice(scandir($output_dir, 0), 2);
        usort($files, function($a, $b) {
            return $a - $b;
        });

        return $files;
    }

    /**
	 * Получить все расширения файлов из допустимых типов MIME.
     * 
     * @return array
	 */
    public static function getExtensionsFromMIMETypes() {       
        return array_map(
            function($item) {
                return ".$item";
            },
            array_keys(self::$available_mime_types)
        );
    }

    /**
	 * Получить расширение файла по типу MIME.
     * 
     * @param string $mime_type
     * @return array
	 */
    private static function getExtansionByMIMEType( $mime_type ) {

        $result = array_filter(
            self::$available_mime_types, 
            function($item) use($mime_type) {
                return $item === $mime_type;
            }
        );

        return !empty($result) ? array_keys($result)[0] : array();
            
    }

    /**
	 * Получить тип MIME по расширению файла.
     * 
     * @param string $file_ext
     * @return array
	 */
    private static function getMimeTypeByExtansion( $file_ext ) {
        $result = array_values(
            array_intersect_key(
                self::$available_mime_types, 
                array_flip( array( $file_ext ) )
            )
        );

        return !empty($result) ? $result[0] : array();
    }

}

?>