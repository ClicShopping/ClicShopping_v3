<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  namespace ClicShopping\OM;

  use ClicShopping\OM\Is;

  use PHPMailer\PHPMailer\Exception;
  use PHPMailer\PHPMailer\PHPMailer;

  class Mail
  {
    protected string $html;
    protected string $text;
    protected string $html_text;
    protected string $lf;
    protected int $debug = 0;
    public string $debugOutput;

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
    protected string $debugFileOutput = CLICSHOPPING::BASE_DIR . 'Work/Log/phpmail_error.log';
    protected PHPMailer $phpMail;

    public function __construct()
    {
      $this->phpMail = new PHPMailer();

      $this->phpMail->XMailer = 'ClicShopping ' . CLICSHOPPING::getVersion();
      $this->phpMail->SMTPDebug = $this->debug;
// test with exit
//      $this->phpMail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";};
      $this->phpMail->debugOutput = $this->debugFileOutput;
      $this->phpMail->CharSet = PHPMailer::CHARSET_UTF8;
      $this->phpMail->WordWrap = 998;
      $this->phpMail->Encoding = 'quoted-printable';


      if (EMAIL_TRANSPORT == 'smtp' || EMAIL_TRANSPORT == 'gmail') {
        $this->phpMail->IsSMTP();

        $this->phpMail->Port = EMAIL_SMTP_PORT;

        if (EMAIL_SMTP_SECURE != 'no') {
          $this->phpMail->SMTPSecure = EMAIL_SMTP_SECURE;
        }

        $this->phpMail->Host = EMAIL_SMTP_HOSTS;
        $this->phpMail->SMTPAuth = EMAIL_SMTP_AUTHENTICATION;

        $this->phpMail->Username = EMAIL_SMTP_USER;
        $this->phpMail->Password = EMAIL_SMTP_PASSWORD;

      } else {
        try {
          $this->phpMail->isSendmail();
        } catch (Exception $e) {
          echo CLICSHOPPING::getDef('error_phpmailer', ['phpmailer_error' => $this->phpMail->ErrorInfo]);
        }
      }

      if (EMAIL_LINEFEED == 'CRLF') {
        $this->lf = "\r\n";
      } else {
        $this->lf = "\n";
      }
    }

    /**
     * nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
     * @param $from
     * @param $to
     * @param $string
     * @return mixed
     */
    private function convertLinefeeds($from, $to, $string): string
    {
      return str_replace($from, $to, $string);
    }

    /**
     * @param string $text
     */
    public function addText(string $text = '')
    {
      $this->phpMail->IsHTML(false);
      $this->text = $this->convertLinefeeds(array("\r\n", "\n", "\r"), $this->lf, $text);
    }

    /**
     * Adds a html part to the mail.
     * Also replaces image names with
     * content-id's.
     * @param string $html
     * @param string $text
     * @param string|null $images_dir
     * @throws Exception
     */
    public function addHtml(string $html, string $text = '', $images_dir = NULL)
    {
      $this->phpMail->IsHTML(true);
      $this->html = $this->convertLinefeeds(array("\r\n", "\n", "\r"), '<br />', $html);
      $this->html_text = $this->convertLinefeeds(array("\r\n", "\n", "\r"), $this->lf, $text);

      if (isset($images_dir)) $this->html = $this->phpMail->msgHTML($this->html, $images_dir);
    }

    /**
     * Adds a html part to the mail.
     * Also replaces image names with
     * content-id's.
     */

    public function addHtmlCkeditor(string $html, $text = NULL, $images_dir = NULL)
    {
      $this->phpMail->IsHTML(true);

      $this->html = $this->convertLinefeeds(array("\r\n", "\n", "\r"), '', $html);
      $this->html_text = $this->convertLinefeeds(array("\r\n", "\n", "\r"), $this->lf, $text);

      if (isset($images_dir)) $this->html = $this->phpMail->msgHTML($this->html, $images_dir);
    }

    /**
     * @param string $encoding
     */
    public function setContentTransferEncoding(string $encoding)
    {
      $this->phpMail->Encoding = $encoding;
    }

    /**
     * @param string $email_address
     * @param string|null $name
     * @return bool
     * @throws Exception
     */
    public function addCC(string $email_address, $name = null)
    {
      return $this->phpMail->addCC($email_address, $name);
    }

    /**
     * @param string $email_address
     * @param string|null $name
     * @return bool
     * @throws Exception
     */
    public function addBCC(string $email_address, $name = null)
    {
      return $this->phpMail->addBCC($email_address, $name);
    }

    /**
     * Clear all recipients
     */
    public function clearTo()
    {
      $this->phpMail->clearAllRecipients();
    }

    /**
     * @param string $charset
     */
    public function setCharset(string $charset)
    {
      $this->phpMail->CharSet = $charset;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addHeader(string $key, string $value)
    {
      $this->phpMail->addCustomHeader($key, $value);
    }

    /**
     * Clear header
     */
    public function clearHeaders()
    {
      $this->phpMail->clearCustomHeaders();
    }

    /**
     *
     * @return mixed
     */
    public function getMailer()
    {
      return $this->phpMail;
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $encoding
     * @param string $type
     * @param string $disposition
     * @throws Exception
     */
    public function addAttachment(string $path, string $name = '', string $encoding = 'base64', string $type = '', string $disposition = 'attachment')
    {
      $this->phpMail->AddAttachment($path, $name, $encoding, $type, $disposition);
    }

    /**
     * @param ?string $to_name
     * @param ?string $to_addr
     * @param ?string $from_name
     * @param ?string $from_addr
     * @param string $subject
     * @param bool $reply_to
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send(?string $to_name = null, ?string $to_addr = null, ?string $from_name = null, string $from_addr, string $subject = '', bool $reply_to = false): bool
    {
      if ((strstr($to_name, "\n") !== false) || (strstr($to_name, "\r") !== false)) {
        return false;
      }

      if ((strstr($to_addr, "\n") !== false) || (strstr($to_addr, "\r") !== false)) {
        return false;
      }

      if ((strstr($subject, "\n") !== false) || (strstr($subject, "\r") !== false)) {
        return false;
      }

      if ((strstr($from_name, "\n") !== false) || (strstr($from_name, "\r") !== false)) {
        return false;
      }

      if ((strstr($from_addr, "\n") !== false) || (strstr($from_addr, "\r") !== false)) {
        return false;
      }

//Set who the message is to be sent from
      $this->phpMail->setFrom($from_addr, $from_addr ?? '');

//Set who the message is to be sent to
      $this->phpMail->AddAddress($to_addr, $to_name ?? '');

      if ($this->validateDomainEmail($to_addr === false) || static::excludeEmailDomain($to_addr) === false) {
        return false;
      }

//Set an alternative reply-to address
      if ($reply_to) {
        $this->phpMail->AddReplyTo(EMAIL_SMTP_REPLYTO, STORE_NAME);
      } else {
        $this->phpMail->AddReplyTo($from_addr, $from_name);
      }

      $this->phpMail->Subject = $subject;

      if (!empty($this->html)) {
        $this->phpMail->Body = $this->html;
        $this->phpMail->AltBody = $this->html_text;

      } else {
        $this->phpMail->Body = $this->text;
      }

      if (EMAIL_TRANSPORT == 'smtp' || EMAIL_TRANSPORT == 'gmail') {

        $this->phpMail->IsSMTP();

        $this->phpMail->Port = EMAIL_SMTP_PORT;

        if (EMAIL_SMTP_SECURE !== 'no') {
          $this->phpMail->SMTPSecure = EMAIL_SMTP_SECURE;
        }

        $this->phpMail->Host = EMAIL_SMTP_HOSTS;
        $this->phpMail->SMTPAuth = EMAIL_SMTP_AUTHENTICATION;
        $this->phpMail->Username = EMAIL_SMTP_USER;
        $this->phpMail->Password = EMAIL_SMTP_PASSWORD;

      } else {
        try {
          $this->phpMail->isSendmail();
        } catch (Exception $e) {
          echo CLICSHOPPING::getDef('error_phpmailer', ['phpmailer_error' => $this->phpMail->ErrorInfo]);
        }
      }

      $error = false;

      if (!$this->phpMail->Send()) {
        $error = true;
      }

      $this->phpMail->clearAddresses();
      $this->phpMail->clearAttachments();

      if ($error === true) {
        return false;
      }

      return true;
    }

    /**
     * Do not send en email if it'excluded by the admin
     * @param string $email
     * @return bool
     */
    public static function excludeEmailDomain(string $email = '')
    {
      if( filter_var( $email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
        $array_domain = explode('@', $email);
        $domain = array_pop($array_domain);
        $exlude_domain = explode(',', CONFIGURATION_EXLCLUDE_EMAIL_DOMAIN);

        if (\is_array($exlude_domain)) {
          foreach ($exlude_domain as $value) {
            if ($value === $domain) {
              return false;
            } else {
              return true;
            }
          }
        }

        return true;
      }
    }
  
    /**
     * @param string $file
     * @return bool
     * @throws Exception
     */
    public function addImage(string $file)
    {
        return $this->phpMail->addAttachment($file, '', PHPMailer::ENCODING_BASE64, '', 'inline');
    }

    /**
     * Send email (text/html) using MIME
     * This is the central mail function. The SMTP Server should be configured
     * @param string|null $to_name The name of the recipient
     * @param string|null $to_email_address The email address of the recipient
     * @param string $email_subject
     * @param string $email_text
     * @param string $from_email_name
     * @param string $from_email_address The email address of the sender
     * @throws Exception
     */
    public function clicMail(?string $to_name = null, string|null $to_email_address = null, string $email_subject = '', string $email_text = '', string $from_email_name = '', string $from_email_address = '')
    {
      if (SEND_EMAILS != 'true') {
        return false;
      }

      if ($this->validateDomainEmail($to_email_address) === false || static::excludeEmailDomain($to_email_address) === false) {
        return false;
      }
      
// Build the text version
      $text = strip_tags($email_text);

      if (EMAIL_USE_HTML == 'true') {
        $this->addHtml($email_text, $text);
      } else {
        $this->addText($text);
      }

      // Send message

      $this->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
    }

    /**
     * Analyse the customer email  domain and validate or not the email
     * @param string|null $email
     * @return bool
     */
    public function validateDomainEmail(?string $email) :bool
    {
      if (Is::EmailAddress($email, true) === false) {
        return false;
      } else {
        return true;
      }
    }
  }