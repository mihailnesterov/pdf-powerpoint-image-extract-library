<?php 
namespace library\helpers;

/**
 * Класс для работы с изображениями с помощью Imagick.
 *
 * @package     prodamus
 * @copyright   2021 Prodamus Ltd. http://prodamus.ru/
 * @author      Mihail Nesterov <mhause@mail.ru>
 * @version     1.0
 * @since       30.08.2021
 */

class ImagickHelper
{

    /**
	 * Проверить, доступен ли формат файла для обработки Imagick.
	 *
     * @param string $file_ext
	 * @return boolean
	 */
    public static function isFileFormatEnabled( $file_ext ) { 
        return in_array( strtoupper($file_ext), self::getEnabledImagickFormats() );
    }


    /**
	 * Получить все доступные форматы файлов, с которыми работает Imagick.
	 *
	 * @return array
	 */
    private static function getEnabledImagickFormats() { 
        
        if( true === self::isImagickLoaded() ) {
            return \Imagick::queryformats();
        }
    }

    /**
	 * Проверить, доступен ли Imagick.
	 *
	 * @return boolean
	 */
    public static function isImagickLoaded() { 

        try {
            if( !extension_loaded('imagick') ) {
                
                echo 'Ошибка! ImageMagick не доступен или не установлен на сервере. Обратитесь к администратору сервера или хостинга';

                exit;
            }
        } catch (Exception $e) {
            echo 'Check imagick loaded exeption: ',  $e->getMessage(), "\n";
        }
        
        return true;
    }

}
?>