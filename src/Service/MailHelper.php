<?php
namespace ShopProject\Service;

use ShopProject\IEnvironment;

class MailHelper
{
    private $mailer;

    public function __construct()
    {
        $params = $this->mailConfigFile();
        $transport = (new \Swift_SmtpTransport($params['smtpHost'], $params['port']))
                        ->setUsername($params['user'])
                        ->setPassword($params['password']);
        $this->mailer = new \Swift_Mailer($transport);
    }
    
    public function send($fromUser, $ToUser, $subject, $content)
    {
        $message = (new \Swift_Message($subject))
                    ->setFrom($fromUser)
                    ->setTo([$ToUser => $ToUser])
                    ->setBody($content, 'text/html');
        $this->mailer->send($message);
    }

    public function customizedContent(string $path, array $params)
    {
        extract($params);
        // return include($path);
        \ob_start();
        include $path;
        return \ob_get_clean();
    }
    

    private function mailConfigFile($fileName = "mail.ini") : array
    {
        $configPath = dirname($_SERVER['DOCUMENT_ROOT']) . "/phpWarehouse/" . IEnvironment::PROJECT_NAME . "/config/$fileName";
        $params = parse_ini_file($configPath);
        return $params;
    }
}
