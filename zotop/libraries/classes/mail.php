<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 邮件发送
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class mail
{
    public $mailer; //发送方式
    public $delimiter; //邮件头分隔符
    public $charset = 'utf-8'; //邮件字符
    public $sender; //发件人
    public $from; //发件邮箱
    public $sign; //签名
    public $smtp_host; //smtp服务器
    public $smtp_port; //smtp端口
    public $smtp_auth; //smtp身份验证
    public $smtp_username; //smtp用户名
    public $smtp_password; //smtp密码

    public $error = '';

    /**
     * 初始化函数
     * 
     * @return void
     */
    public function __construct()
    {
        $this->mailer           = c('system.mail_mailer');
        $this->delimiter        = c('system.mail_delimiter');
        $this->sender           = c('system.mail_sender');
        $this->from             = c('system.mail_from');
        $this->sign             = c('system.mail_sign');
        $this->smtp_host        = c('system.mail_smtp_host');
        $this->smtp_port        = c('system.mail_smtp_port');
        $this->smtp_auth        = c('system.mail_smtp_auth');
        $this->smtp_username    = c('system.mail_smtp_username');
        $this->smtp_password    = c('system.mail_smtp_password');
    }

    /**
     * 发送邮件
     * 
     * @param string $sendto 收件邮箱 
     * @param string $subject 邮件主题
     * @param string $message 邮件内容
     * @param string $from 发送邮箱
     * @return
     */
    public function send($sendto, $subject, $message, $from = null)
    {
        $this->delimiter = $this->delimiter == 1 ? "\r\n" : ($this->delimiter == 2 ? "\r" : "\n");
        $this->sender = empty($this->sender) ? 'zotop' : $this->sender;

        //发件人 zotop <zotopcms@qq.com> 发件人和邮箱地址之间必须含有一个空格
        if (is_null($from))
        {
            $from = '=?' . $this->charset . '?B?' . base64_encode($this->sender) . "?= <$this->from>";
        }
        else
        {
            $from = preg_match('/^(.+?) \<(.+?)\>$/', $from, $m) ? '=?' . $this->charset . '?B?' . base64_encode($m[1]) . "?= <$m[2]>" : $from;
        }

        //收件人，支持多个收件人用英文逗号隔开
        $sendtos = array();
        if (strpos($sendto, ','))
        {
            foreach (explode(',', $sendto) as $to)
            {
                $sendtos[] = preg_match('/^(.+?) \<(.+?)\>$/', $to, $m) ? '=?' . $this->charset . '?B?' . base64_encode($m[1]) . "?= <$m[2]>" : $to;
            }
            $sendto = implode(',', $sendtos);
        }

        //邮件主题
        $subject = '=?' . $this->charset . '?B?' . base64_encode(str_replace("\r", '', str_replace("\n", '', $subject))) . '?=';

        //邮件内容
        $message .= empty($this->sign) ? '' : '<div style="margin-top:20px;padding:10px 0;border-top:dotted 1px #909090;font:normal 14px/1.2 "Microsoft YaHei",Arial,Narrow;color:#000;"><sign>' . $this->sign . '</sign></div>';
        $message = chunk_split(base64_encode(str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $message)))))));

        //邮件头
        $headers = "From: $from{$this->delimiter}X-Priority: 3{$this->delimiter}X-Mailer: zotop{$this->delimiter}MIME-Version: 1.0{$this->delimiter}Content-type: text/html; charset=$this->charset{$this->delimiter}Content-Transfer-Encoding: base64{$this->delimiter}";

        //发送邮件
        if ($this->mailer == 1)
        {
            //通过 PHP 函数的 sendmail 发送
            if (@mail($sendto, $subject, $message, $headers))
            {
                return true;
            }
            return $this->error(t('系统不支持使用当前的发送方式'), 1);
        }
        elseif ($this->mailer == 2)
        {
            //通过 SOCKET 连接 SMTP 服务器发送(支持 ESMTP 验证)
            return $this->smtp($sendto, $subject, $message, $headers, $from);
        }
        else
        {
            //通过 PHP 函数 SMTP 发送 Email(仅 Windows 主机下有效, 不支持 ESMTP 验证)
            if ($this->mailto($sendto, $subject, $message, $headers, $from))
            {
                return true;
            }

            return $this->error(t('系统不支持使用当前的发送方式'), 1);
        }
    }

    //通过 PHP 函数 SMTP 发送 Email(仅 Windows 主机下有效, 不支持 ESMTP 验证)
    function mailto($sendto, $subject, $message, $headers, $from)
    {
        ini_set('SMTP', $this->smtp_host);
        ini_set('smtp_port', $this->smtp_port);
        ini_set('sendmail_from', $from);
        return @mail($sendto, $subject, $message, $headers);
    }

    //通过 SOCKET 连接 SMTP 服务器发送(支持 ESMTP 验证)
    function smtp($to, $subject, $message, $headers, $from)
    {
        if (! $fp = @fsockopen($this->smtp_host, $this->smtp_port, $code, $error, 10))
        {
            //return $this->error($error, $code);
            return $this->error(t('无法连接smtp服务器，SMTP服务器地址或者端口错误'), $code);
        }

        stream_set_blocking($fp, true);
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != '220')
        {
            return $this->error($lastmessage, substr($lastmessage, 0, 3));
        }

        fputs($fp, ($this->smtp_auth ? 'EHLO' : 'HELO') . " zotop\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250)
        {
            return $this->error($lastmessage, substr($lastmessage, 0, 3));
        }

        while (1)
        {
            if (substr($lastmessage, 3, 1) != '-' || empty($lastmessage)) break;
            $lastmessage = fgets($fp, 512);
        }

        if ($this->smtp_auth)
        {
            fputs($fp, "AUTH LOGIN\r\n");
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 334)
            {
                return $this->error($lastmessage, substr($lastmessage, 0, 3));
            }

            fputs($fp, base64_encode($this->smtp_username) . "\r\n");
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 334)
            {
                //return $this->error(t('SMTP用户名或者密码不正确'), $code);
                return $this->error($lastmessage, substr($lastmessage, 0, 3));
            }

            fputs($fp, base64_encode($this->smtp_password) . "\r\n");
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 235)
            {
                //return $this->error(t('SMTP用户名或者密码不正确'), $code);
                return $this->error($lastmessage, substr($lastmessage, 0, 3));
            }
        }

        fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $from) . ">\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 250)
        {
            fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $from) . ">\r\n");
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 250)
            {
                //return $this->error(t('SMTP服务器需要身份验证'), $code);
                return $this->error($lastmessage, substr($lastmessage, 0, 3));
            }
        }

        $email_tos = array();
        foreach (explode(',', $to) as $touser)
        {
            $touser = trim($touser);
            if ($touser)
            {
                fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser) . ">\r\n");
                $lastmessage = fgets($fp, 512);
                if (substr($lastmessage, 0, 3) != 250)
                {
                    fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser) . ">\r\n");
                    $lastmessage = fgets($fp, 512);
                    return $this->error($lastmessage, substr($lastmessage, 0, 3));
                }
            }
        }

        fputs($fp, "DATA\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 354)
        {
            return $this->error($lastmessage, substr($lastmessage, 0, 3));
        }

        $headers .= 'Message-ID: <' . gmdate('YmdHs') . '.' . substr(md5($message . microtime()), 0, 6) . rand(100000, 999999) . '@' . $_SERVER['HTTP_HOST'] . ">{$this->delimiter}";

        fputs($fp, "Date: " . gmdate('r') . "\r\n");
        fputs($fp, "To: " . $to . "\r\n");
        fputs($fp, "Subject: " . $subject . "\r\n");
        fputs($fp, $headers . "\r\n");
        fputs($fp, "\r\n\r\n");
        fputs($fp, $message . "\r\n.\r\n");

        $lastmessage = fgets($fp, 512);

        if (substr($lastmessage, 0, 3) != 250)
        {
            return $this->error($lastmessage, substr($lastmessage, 0, 3));
        }
        
        fputs($fp, "QUIT\r\n");
        return true;
    }

    /**
     * 返回错误
     *
     * <code>
     *
     * return $this->error(error);
     *
     * </code>
     *
     */
	public function error($error = '')
	{
		if ( empty($error) )
		{
			return $this->error ? $this->error : false;
		}

		$this->error = $error;

		return false;
	}
}
?>