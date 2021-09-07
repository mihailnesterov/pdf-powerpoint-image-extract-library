<?php 
namespace library\helpers;

/**
 * Класс для проверки наличия PHP-функций и команд OS, необходимых для работы библиотеки.
 *
 * @package     prodamus
 * @copyright   2021 Prodamus Ltd. http://prodamus.ru/
 * @author      Mihail Nesterov <mhause@mail.ru>
 * @version     1.0
 * @since       30.08.2021
 */

class EnvHelper
{

    /**
	 * Проверить, доступна ли PHP-функция.
	 *
     * @param string $func_name
	 * @return boolean
	 */
    public static function isFunctionEnabled( $func_name ) { 
        $is_function_enabled = false;
        
        try {
            $is_function_enabled = function_exists( $func_name ) &&
                !in_array( $func_name, array_map('trim', explode(', ', ini_get('disable_functions'))) ) &&
                strtolower( ini_get('safe_mode') ) != 1;
        } catch (Exception $e) {
            echo 'Check function enabled exeption: ',  $e->getMessage(), "\n";
        }
        
        return $is_function_enabled;
    }

    /**
	 * Проверить, доступна ли команда OS.
	 *
     * @param string $command
	 * @return boolean
	 */
    public static function isCommandEnabled( $command ) {
        $is_command_enabled = false;
        
        try {
            $is_command_enabled = exec( sprintf("which %s", escapeshellarg($command)) ) === '' ? false : true;
        } catch (Exception $e) {
            echo 'Check function enabled exeption: ',  $e->getMessage(), "\n";
        }
        
        return $is_command_enabled;
    }
    
}
?>