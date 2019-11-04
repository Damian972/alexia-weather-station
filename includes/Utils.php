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

    public function redirectToHome(): void 
    {
        header('Location: '.BASE_URL);
        exit();
    }

    public static function redirectToSame(): void
    {
        #Redirect user on same page to unset $_POST
        header('Location: '.$_SERVER['PHP_SELF']);
        exit();
    }

    public static function sendPushbulletNotif(string $title, string $message, string $auth_token)
    {
        $data = json_encode(['type' => 'note', 'title' => $title, 'body' => $message]);
        $curl = curl_init('https://api.pushbullet.com/v2/pushes');                                                                      
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                                                                  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Access-Token: '  .$auth_token,                                                                     
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data))                                                                       
        );                                                                                                                                                                                                                   
        $result = curl_exec($curl);
    }

    public static function sendMail(array $emails, string $title, string $template, array $template_params = []): void
    {
        if (empty($emails)) return;
        $headers = "From: " . WEBSITE_NAME . "\r\n";
        //$headers .= "Reply-To: ". WEBSITE_NAME . "\r\n";
        //$headers .= "CC: susan@example.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $smarty = self::getSmartyInstance();
        $smarty->assign($template_params);
        $html =  $smarty->fetch($template);

        foreach ($emails as $email) {
            mail($email, '['.WEBSITE_NAME. '] '.$title, $html,$headers);
        }
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