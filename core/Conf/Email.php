<?php namespace Core\Conf;

class Email
{
    /**
     * Email Config
     */
    public function start()
    {
        $config = [
            'Protocol'       => 'smtp',
            'SMTP_Host'      => 'smtp.gmail.com',
            'SMTP_Username'  => 'email@gmail.com',
            'SMTP_Password'  => 'smtp_password_here',
            'SMTP_Port'      => 465,
            'SMTP_Crypto'    => 'ssl'
        ];
        return new \Core\Conf\Kyaaaa\Mailer($config['SMTP_Host'], $config['SMTP_Port'], $config['SMTP_Crypto'], $config['SMTP_Username'], $config['SMTP_Password']);
    }
}
