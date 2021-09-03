<?php 
namespace library\helpers;

/**
 * Класс для обработки ошибок при загрузке и обработке файлов.
 *
 * @package     prodamus
 * @copyright   2021 Prodamus Ltd. http://prodamus.ru/
 * @author      Mihail Nesterov <mhause@mail.ru>
 * @version     1.0
 * @since       30.08.2021
 */
class ErrorHelper
{

    /**
     * Коды возможных ошибок при загрузке файла и их описание
     * 
     * @var array
     */
    private static $php_file_upload_errors = array(
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    );

    /**
	 * Обработать ошибки UPLOAD_ERR.
     * 
     * @param string $error
	 */
    public static function getFileUploadError($error) {
        try {
            if ( $error > 0 ) {
                echo sprintf(
                    'UPLOAD_ERR. Код ошибки: %s (%s)',
                    $error,
                    self::getErrorByCode( $error )
                );
                exit;
            }    
        } catch (Exception $e) {
            echo 'File upload exeption: ',  $e->getMessage(), "\n";
        }
    }

    /**
	 * Получить описание ошибки по ее коду.
	 *
     * @param int $code
	 * @return string
	 */
    private static function getErrorByCode( $code ) {
        
        $error = '';
        
        try {
            $errors = self::$php_file_upload_errors;
            
            if ( array_key_exists($code, $errors) ) {
                $error = array_values(
                    array_intersect_key(
                        $errors, 
                        array_flip( array( $code ) )
                    )
                )[0];
            }
        } catch (Exception $e) {
            echo 'Getting error by code exeption: ',  $e->getMessage(), "\n";
        }

        return $error;
    }

    /**
	 * Вывести ошибку при загрузке файла недопустимого MIME типа.
     * 
     * @param string $file_type
     * @param array $available_mime_types
	 */
    public static function getMimeTypesErrors($file_type, $available_mime_types) {
        try {
            if( !in_array($file_type, $available_mime_types) ) {
                echo sprintf(
                    'MIME_TYPE_ERR. Недопустипый тип файла: %s ',
                    $file_type
                );
                
                exit; 
            }
        } catch (Exception $e) {
            echo 'Getting MIME type error exeption: ',  $e->getMessage(), "\n";
        }
    }
    
}
?>