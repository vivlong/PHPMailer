<?php
namespace PhalApi\PHPMailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * 邮件工具类
 *
 * - 基于PHPMailer的邮件发送
 *
 *  配置
 *
 * 'PHPMailer' => array(
 *   'email' => array(
 *       'host' => 'smtp.gmail.com',
 *       'username' => 'XXX@gmail.com',
 *       'password' => '******',
 *       'from' => 'XXX@gmail.com',
 *       'fromName' => 'PhalApi团队',
 *       'sign' => '<br/><br/>请不要回复此邮件，谢谢！<br/><br/>-- PhalApi团队敬上 ',
 *   ),
 * ),
 *
 * 示例
 *
 * $mailer = new PHPMailer_Lite(true);
 * $mailer->send('chanzonghuang@gmail.com', 'Test PHPMailer Lite', 'something here ...');
 *
 * @author dogstar <chanzonghuang@gmail.com> 2015-2-14
 */

class Lite
{
    protected $debug;

    protected $config;

    public function __construct($debug = FALSE) {
        $this->debug = $debug;

        $this->config = \PhalApi\DI()->config->get('app.PHPMailer.email');
    }

    /**
     * 发送邮件
     * @param array/string $addresses 待发送的邮箱地址
     * @param sting $title 标题
     * @param string $content 内容
     * @param boolean $isHtml 是否使用HTML格式，默认是
     * @return boolean 是否成功
     */
    public function send($addresses, $title, $content, $isHtml = TRUE)
    {
        $mail = new PHPMailer;
        $cfg = $this->config;
        $mail->isSMTP();
        $mail->Host = $cfg['host'];
        $mail->SMTPAuth = true;
		$mail->SMTPSecure = $cfg['SMTPSecure'];
		$mail->Port = $cfg['port'];
        $mail->Username = $cfg['username'];
        $mail->Password = $cfg['password'];
        $mail->CharSet = 'utf-8';
        $mail->From = $cfg['username'];
        $mail->FromName = $cfg['fromName'];
        $addresses = is_array($addresses) ? $addresses : array($addresses);
        foreach ($addresses as $address) {
            if(!empty($address)){
                $mail->addAddress($address);
            }
        }
        $mail->WordWrap = 50;
        $mail->isHTML($isHtml);
        $mail->Subject = trim($title);
        $mail->Body = $content . $cfg['sign'];
        if (!$mail->send()) {
            if ($this->debug) {
                \PhalApi\DI()->logger->debug('Fail to send email with error: ' . $mail->ErrorInfo);
            }
            return false;
        }
        if ($this->debug) {
            \PhalApi\DI()->logger->debug('Succeed to send email', array('addresses' => $addresses, 'title' => $title));
        }
        return true;
    }

    /**
     * 发送邮件
     * @param array/string $addresses 待发送的邮箱地址
     * @param sting $title 标题
     * @param string $content 内容
     * @param boolean $isHtml 是否使用HTML格式，默认是
     * @return boolean 是否成功
     */
    public function sendWithAttachment($addresses, $title, $content, $filePath)
    {
        $mail = new PHPMailer;
        $cfg = $this->config;
        $mail->isSMTP();
        $mail->Host = $cfg['host'];
        $mail->SMTPAuth = true;
		$mail->SMTPSecure = $cfg['SMTPSecure'];
		$mail->Port = $cfg['port'];
        $mail->Username = $cfg['username'];
        $mail->Password = $cfg['password'];
        $mail->CharSet = 'utf-8';
        $mail->From = $cfg['username'];
        $mail->FromName = $cfg['fromName'];
        $addresses = is_array($addresses) ? $addresses : array($addresses);
        foreach ($addresses as $address) {
            if(!empty($address)){
                $mail->addAddress($address);
            }
        }
        $mail->WordWrap = 50;
        $mail->AddAttachment($filePath);
        $mail->isHTML(TRUE);
        $mail->Subject = trim($title);
        $mail->Body = $content;
        if (!$mail->send()) {
            if ($this->debug) {
                \PhalApi\DI()->logger->debug('Fail to send email with error: ' . $mail->ErrorInfo);
            }
            return false;
        }
        if ($this->debug) {
            \PhalApi\DI()->logger->debug('Succeed to send email', array('addresses' => $addresses, 'title' => $title));
        }
        return true;
    }
}
