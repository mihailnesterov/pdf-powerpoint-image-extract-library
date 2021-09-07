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
     * Коды возможных ошибок при загрузке файла UPLOAD_ERR и их описание
     * 
     * @var array
     */
    private static $file_upload_errors = array(
        0 => 'Файл успешно загружен',
        1 => 'Превышен допустимый размер файла upload_max_filesize, установленный в конфигурационном файле php.ini',
        2 => 'Превышен допустимый размер файла MAX_FILE_SIZE, установленный в HTML форме',
        3 => 'Файл загружен не полностью',
        4 => 'Файл не выбран',
        6 => 'Не доступен временный каталог для сохранения файла',
        7 => 'Ошибка записи файла на диск',
        8 => 'Загрузка файла заблокирована одним из расширений PHP'
    );

    /**
     * Сообщения для пользователя, как справиться с ошибками.
     * 
     * @var array
     */
    private static $file_upload_help_messages = array(
        0 => '',
        1 => 'Обратитесь к администратору сервера или хостинга',
        2 => 'Проверьте значение MAX_FILE_SIZE в HTML форме или обратитесь к администратору сервера или хостинга',
        3 => 'Обратитесь к администратору сервера или хостинга',
        4 => 'Выберите файл для загрузки и повторите попытку',
        6 => 'Обратитесь к администратору сервера или хостинга',
        7 => 'Обратитесь к администратору сервера или хостинга',
        8 => 'Обратитесь к администратору сервера или хостинга'
    );

    /**
	 * Обработать ошибки загрузки файла UPLOAD_ERR.
     * 
     * @param string $code
	 */
    public static function getFileUploadError($code) {
        try {
            if ( $code > 0 ) {
                echo sprintf(
                    'UPLOAD_ERR. Код ошибки: %s (%s). %s',
                    $code,
                    self::getValueByKey( $code, self::$file_upload_errors ),
                    self::getValueByKey( $code, self::$file_upload_help_messages )
                );
                exit;
            }    
        } catch (Exception $e) {
            echo 'File upload error exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
	 * Получить элемент массива по ключу.
	 *
     * @param int $key
     * @param array $array
	 * @return string
	 */
    private static function getValueByKey( $key, $array ) {
        
        $value = '';
        
        try {            
            if ( array_key_exists($key, $array) ) {
                $value = array_values(
                    array_intersect_key(
                        $array, 
                        array_flip( array( $key ) )
                    )
                )[0];
            }
        } catch (Exception $e) {
            echo 'Getting array value by key exception: ',  $e->getMessage(), "\n";
        }

        return $value;
    }

    /**
	 * Вывести ошибку при загрузке файла недопустимого MIME типа.
     * 
     * @param array $file
     * @param array $available_mime_types
	 */
    public static function getMimeTypesErrors($file, $available_mime_types) {
        try {
            if( !in_array($file['type'], $available_mime_types) ) {
                echo sprintf(
                    'MIME_TYPE_ERR. Файл "%s" не может быть обработан, так как данный формат не поддерживается. Выберите другой файл и повторите попытку.',
                    $file['name']
                );
                
                exit; 
            } else {
                echo self::$file_upload_errors[0];
            }
        } catch (Exception $e) {
            echo 'Getting MIME type error exception: ',  $e->getMessage(), "\n";
        }
    }
    
}
?>