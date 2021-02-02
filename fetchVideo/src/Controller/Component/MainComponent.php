<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\App;
use Cake\Controller\ComponentRegistry;
use Cake\Routing\Router;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require ROOT.'/vendor/autoload.php';

require ROOT.'/vendor/phpmailer/phpmailer/src/Exception.php';
require ROOT.'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require ROOT.'/vendor/phpmailer/phpmailer/src/SMTP.php';

/**
 * Main component
 */
class MainComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /*
    * Email Configuration
    * @params : array $config [Cake Email Params]
    *        - $config[to]   - Email of receiver
    *        - $config[from] - Email of sender
    *        - $cofig[message] - Message of email
    *          - $config[subject] - subject of email
    * @return : boolean
    */

    public function sendEmail($config = array()){ //pr($config); exit;
            $mail = new PHPMailer();
            $mail->Host = "localhost";
            $mail->Username   = "xxx";
            $mail->Password   = "xxx";
            $mail->SMTPSecure =  "tls";
            $mail->Port = 587;
            $mail->SMTPOptions = [
                'tls' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
            $mail->From = "maksoldinjo8@gmail.com";
            $mail->FromName = "From Me";

            $mail->addAddress($config['to']);

            $mail->isHTML(true);

            $mail->Subject = $config['subject'];
            $mail->Body = nl2br($config['body']);
            if(!$mail->Send()) {
                return array('error' => true, 'message' => 'Mailer Error: ' . $mail->ErrorInfo);
            } else {
                return array('error' => false, 'message' =>  "Message sent!");
            }
    }
}
