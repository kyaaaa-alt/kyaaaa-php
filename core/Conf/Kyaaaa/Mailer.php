<?php namespace Core\Conf\Kyaaaa;

class Mailer
{
    const NL = "\r\n";

    private $host;
    private $port;
    private $secure;
    private $username;
    private $password;

    private $from = [];
    private $to = [];
    private $cc = [];
    private $bcc = [];
    private $replyTo = [];
    private $recipients = [];

    private $files = [];
    private $charset  = 'utf-8';
    private $encoding = '8bit';
    private $subject;
    private $body;
    private $text;

    private $connection;
    private $timeout = 30;
    private $extensions = [];

    private $hostname;
    private $debug = false;

    public function __construct($host = 'localhost', $port = 25, $secure = '', $username = '', $password = '')
    {
        $this->host = $host;
        $this->port = (int) $port;
        $this->secure = strtolower($secure);
        $this->username = $username;
        $this->password = $password;

        if (PHP_SAPI === 'cli') {
            $this->debug = true;
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    public function setFrom($address, $name = '')
    {
        if ($address = $this->valid($address)) {
            $this->from = [$address, $this->stripnl($name)];
        }
    }

    public function addReplyTo($address, $name = '')
    {
        if ($address = $this->valid($address)) {
            if(!isset($this->replyTo[$address])) {
                $this->replyTo[$address] = [$address, $this->stripnl($name)];
            }
        }
    }

    public function setTo($address, $name = '')
    {
        $this->addTo($address, $name);
    }

    public function addTo($address, $name = '')
    {
        if ($address = $this->valid($address)) {
            if(!isset($this->recipients[$address])) {
                $this->to[] = [$address, $this->stripnl($name)];
                $this->recipients[$address] = true;
            }
        }
    }

    public function addCc($address, $name = '')
    {
        if ($address = $this->valid($address)) {
            if(!isset($this->recipients[$address])) {
                $this->cc[] = [$address, $this->stripnl($name)];
                $this->recipients[$address] = true;
            }
        }
    }

    public function addBcc($address, $name = '')
    {
        if ($address = $this->valid($address)) {
            if(!isset($this->recipients[$address])) {
                $this->bcc[] = [$address, $this->stripnl($name)];
                $this->recipients[$address] = true;
            }
        }
    }

    public function addFile($file)
    {
        if ($file && is_file($file)) {
            $this->files[] = $file;
        }
    }

    private function init()
    {
        if (strpos($this->host, ':')) {
            $this->host = preg_replace(['#[\w]+://#', '#:[\w]+#'], '', $this->host);
        }

        if (empty($this->to)) {
            throw new \Exception('ERROR: A valid email address to send to is required');
        }

        if (strlen(trim($this->body)) < 3) {
            throw new \Exception('ERROR: Message body empty');
        }

        if ($this->secure === 'ssl') {
            $this->host = $this->secure . '://' . $this->host;
        }

        if (empty($this->from)) {
            $this->from = [$this->username, ''];
        }

        if (empty($this->hostname)) {
            $this->hostname = gethostname();
        }
    }

    public function send()
    {
        try {
            $this->init();
            $this->connect();
            $this->auth();

            $this->request('MAIL FROM: <'.$this->from[0].'>', 250);
            $addresses = array_keys($this->recipients);
            foreach($addresses as $address) {
                $this->request('RCPT TO: <'. $address . '>', 250);
            }

            $this->request('DATA', 354);
            $this->request($this->createMessage(), 250);
            $this->request('QUIT', 221);

            return true;
        } catch(\Exception $e) {
            $this->debug($e->getMessage());
            return false;
        }

        return true;
    }

    public function setSubject($subject = '')
    {
        $this->subject = $subject;
    }

    public function setBody($body = '')
    {
        $this->body = $body;
    }

    public function createMessage()
    {
        $message = [];
        $message[]  = $this->createHeader();
        $message[] = $this->createContent();
        return implode(static::NL, $message);
    }

    private function createHeader()
    {
        $headers[] = 'Date: '. date('r');
        $headers[] = 'To: '.$this->concatAddress($this->to);
        $headers[] = 'From: '.$this->formatAddress($this->from);

        if (!empty($this->cc)) {
            $headers[] = 'Cc: '.$this->concatAddress($this->cc);
        }

        if (!empty($this->bcc)) {
            $headers[] = 'Bcc: '.$this->concatAddress($this->bcc);
        }

        if (!empty($this->replyTo)) {
            $headers[] = 'Reply-To: '.$this->concatAddress($this->replyTo);
        }

        $headers[] = iconv_mime_encode('Subject', $this->subject);
        $headers[] = 'Message-ID: '. $this->generateMessageId();
        $headers[] = 'X-Mailer: SMTPMailer v1.0.0 https://github.com/smtpmailer/smtpmailer';
        $headers[] = 'MIME-Version: 1.0';

        return implode(static::NL, $headers);
    }

    private function createContent()
    {
        $boundary = md5(uniqid());

        $contents = [];

        if (empty($this->text)) {
            $this->text = $this->html2text($this->body);
        }

        $contents[] = 'Content-Type: multipart/'. (!empty($this->files) ? 'mixed' : 'alternative') .'; boundary="'.$boundary.'"';
        $contents[] = '';
        $contents[] = 'This is a multi-part message in MIME format.';
        $contents[] = '--'. $boundary;

        $contents[] = $this->formatContent('plain', 'text');
        $contents[] = '--'. $boundary;

        $contents[] = $this->formatContent('html', 'body');
        $contents[] = '--'. $boundary;

        if (!empty($this->files)) {
            $contents[] = $this->createAttachment($boundary);
        }

        $contents[count($contents)-1] .= '--';
        $contents[] = ".";

        return implode(static::NL, $contents);
    }

    private function formatContent($type, $content)
    {
        $contents[] = 'Content-Type: text/'.$type.'; charset="'.$this->charset.'"';
        $contents[] = 'Content-Transfer-Encoding: '. $this->encoding;
        $contents[] = '';
        $contents[] = ($this->encoding == 'quoted-printable') ? quoted_printable_encode($this->$content) : $this->$content;
        return implode(static::NL, $contents);
    }

    private function createAttachment($boundary)
    {
        $contents = [];
        foreach ($this->files as $file) {
            if (file_exists($file)) {
                $contents[] = 'Content-Type: application/octet-stream; '.'name="'. basename($file) .'"';
                $contents[] = 'Content-Transfer-Encoding: base64';
                $contents[] = 'Content-Disposition: attachment';
                $contents[] = '';
                $contents[] = chunk_split(base64_encode(file_get_contents($file)));
                $contents[] = '--'.$boundary;
            }
        }
        return implode(static::NL, $contents);
    }

    public function connect()
    {
        $this->debug("Connecting to {$this->host}:{$this->port}");

        $this->connection = @stream_socket_client($this->host . ':' . $this->port, $errno, $errstr, $this->timeout);

        if (!$this->connection) {
            extract(error_get_last());
            throw new \Exception("ERROR: $errstr in $file on line $line");
        }

        $this->debug('Connecting successfully');

        $this->response(220);

        $this->request('EHLO '.$this->hostname, 250);

        if ($this->secure === 'tls' || isset($this->extensions['STARTTLS'])) {
            $this->request('STARTTLS', 220);
            stream_socket_enable_crypto($this->connection, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        }

        return true;
    }

    private function auth()
    {
        if (!empty($this->extensions['AUTH'])) {

            if (empty($this->username) || empty($this->password)) {
                throw new \Exception('ERROR: SMTP username/password for '. $this->host . ' is required');
            }

            $authType = $this->extensions['AUTH'];

            switch($authType) {
                case 'PLAIN':
                    $this->request('AUTH PLAIN', 334);
                    $this->request(base64_encode("\0" . $this->username . "\0" . $this->password), 235);
                    break;
                case 'LOGIN':
                    $this->request('AUTH LOGIN', 334);
                    $this->request(base64_encode($this->username), 334);
                    $this->request(base64_encode($this->password), 235);
                    break;
                default:
                    throw new \Exception('ERROR: Authentication method "'. $authType . '" is not supported');
            }
        }
        return true;
    }

    private function request($cmd, $code)
    {
        $this->debug('REQUEST: ' . $cmd);
        fwrite($this->connection, $cmd . static::NL);

        $prefix = substr($cmd, 0, 4);
        return $this->response($code, $prefix);
    }

    private function response($code, $prefix = '')
    {
        stream_set_timeout($this->connection, $this->timeout);
        $result = fread($this->connection, 768);

        $meta = stream_get_meta_data($this->connection);
        if ($meta['timed_out']) {
            throw new \Exception('ERROR: Stream socket timed-out (' . $this->timeout . 's)');
        }

        if (substr($result, 0, 3) != $code) {
            throw new \Exception($result);
        }

        $this->debug("RESPONSE: " . $result);

        if ($prefix === 'EHLO') {
            $this->parseEhloResponse($result);
        } elseif ($prefix === 'QUIT') {
            $this->close();
        }

        return true;
    }

    private function close()
    {
        $this->extensions = null;
        if ($this->connection) {
            fclose($this->connection);
            $this->connection = null;
            $this->debug('Connection: closed');
        }
    }

    private function parseEhloResponse($str)
    {
        $ary = explode("\n", trim(str_replace(["\r\n", "\r"], "\n", $str)));
        unset($ary[0]);
        foreach($ary as $ext) {
            $v = explode(" ", substr($ext, 4));
            $key = $v[0];
            if (isset($v[1])) {
                unset($v[0]);
                $this->extensions[$key] = $v[1];
            } else {
                $this->extensions[$key] = true;
            }
        }
    }

    private function debug($str)
    {
        if (!$this->debug) {
            return;
        }
        $str = preg_replace('/\r\n|\r/ms', "\n", $str);
        echo "[", date('Y-m-d H:i:s'), "] ", trim(str_replace("\n","\n" . str_repeat("\t", 4), trim($str))), "\n";
    }

    private function valid($address)
    {
        $address = strtolower($address);
        $address = filter_var($address, FILTER_SANITIZE_EMAIL);
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            $this->debug('Invalid address: '. $address);
            return false;
        }
        return $address;
    }

    public function setTimeout($seconds = 30)
    {
        $this->timeout = (int) $seconds;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public static function stripnl($str)
    {
        return trim(str_replace(["\r", "\n"], '', $str));
    }

    private function formatAddress($address)
    {
        if (empty($address[1])) {
            return $address[0];
        }
        return '"'. preg_replace('#^:\s+#', '', iconv_mime_encode('', $address[1])) .'" <'.$address[0].'>';
    }

    private function concatAddress($addresses)
    {
        $list = [];
        foreach ($addresses as $address) {
            $list[] = $this->formatAddress($address);
        }
        return implode(', ', $list);
    }

    private function generateMessageId()
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40);
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80);
        return '<' . vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4)) . '@' . $this->hostname .'>';
    }

    public function html2text($html)
    {
        $html = html_entity_decode($html, ENT_QUOTES, $this->charset);
        $html = strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/si', '', $html), '<br>');
        return trim(str_replace('<br>', "\n", $html));
    }
}