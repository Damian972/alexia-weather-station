<?php

use Medoo\Medoo;

class Utils{

    public static function setFlash(string $message, string $type = ''): void
    {
        $_SESSION['notification']['message'] = ucfirst($message);
        $_SESSION['notification']['type'] = strtolower($type);
    }

    public static function clearFlash(): void
    {
        unset($_SESSION['notification']);
    }

    public static function clearFormLastErrors(): void
    {
        unset($_SESSION['form_errors']);
    }

    public static function encryptData(string $data): string
    {
        return hash_hmac('sha512', SALT_KEY, $data);
    }

    public static function isLogged(): bool
    {
        if (NEED_LOGIN) return !empty($_SESSION['user']);
        return true;
    }

    public static function getOptionsFromDb(Medoo $db): array
    {
        $options = [];
        $result = $db->select('options', ['name', 'value']);
        if (is_array($result)) {
            foreach ($db->select('options', ['name', 'value']) as $option) {
                $options[$option['name']] = $option['value'];
            }
        }
        return $options;
    }

    public static function getDatabase(): Medoo
    {
        return ('mysql' === strtolower(DB_TYPE)) ? 
            (new Medoo([
                'database_type' => DB_TYPE,
                'database_name' => DB_NAME,
                'server' => DB_HOST,
                'username' => DB_USER,
                'password' => DB_PASSWORD
            ])) : (new Medoo([
                'database_type' => 'sqlite',
                'database_file' => VARF.'/'.DB_NAME.'.db'
            ]));
    }

    public static function getSmartyInstance()
    {
        
        return (new Smarty())->setTemplateDir(TEMPLATES)
        ->setCompileDir(VARF.'/templates')
        ->setCacheDir(CACHE)
        ->assign('is_logged', self::isLogged());
    }

    public function redirectToHome(): void {
        header('Location: '.BASE_URL);
        exit();
    }

    public static function redirectToSame(): void
    {
        #Redirect user on same page to unset $_POST
        header('Location: '.$_SERVER['PHP_SELF']);
        exit();
    }

    public static function debug($var): void
    {
        if (DEBUG) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
    }
}