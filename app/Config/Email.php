<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
	public $fromEmail = 'admin@rental.qithy.com'; // Email pengirim
	public $fromName  = 'Rental Qithy';          // Nama pengirim
	public $protocol  = 'smtp'; // mail, sendmail, smtp
	public $SMTPHost  = 'mail.rental.qithy.com'; // Mail server
	public $SMTPUser  = 'admin@rental.qithy.com'; // Email akun
	public $SMTPPass  = '5Ahlussunnah6';   // Password akun
	public $SMTPPort  = 587;                     // Port SMTP
	public $SMTPCrypto = 'tls';  //tls or ssl
	public $mailType  = 'html'; //'text' or 'html'
	public $charset   = 'utf-8'; //Character set (utf-8, iso-8859-1, etc.)
	public $wordWrap  = true;
	public $validate = false; // Jika ingin memvalidasi email (opsional)



	/**
	 * @var string
	 */
	public $recipients;

	/**
	 * The "user agent"
	 *
	 * @var string
	 */
	public $userAgent = 'CodeIgniter';



	/**
	 * The server path to Sendmail.
	 *
	 * @var string
	 */
	public $mailPath = '/usr/sbin/sendmail';





	/**
	 * SMTP Timeout (in seconds)
	 *
	 * @var integer
	 */
	public $SMTPTimeout = 5;

	/**
	 * Enable persistent SMTP connections
	 *
	 * @var boolean
	 */
	public $SMTPKeepAlive = false;





	/**
	 * Character count to wrap at
	 *
	 * @var integer
	 */
	public $wrapChars = 76;







	/**
	 * Email Priority. 1 = highest. 5 = lowest. 3 = normal
	 *
	 * @var integer
	 */
	public $priority = 3;

	/**
	 * Newline character. (Use “\r\n” to comply with RFC 822)
	 *
	 * @var string
	 */
	public $CRLF = "\r\n";

	/**
	 * Newline character. (Use “\r\n” to comply with RFC 822)
	 *
	 * @var string
	 */
	public $newline = "\r\n";

	/**
	 * Enable BCC Batch Mode.
	 *
	 * @var boolean
	 */
	public $BCCBatchMode = false;

	/**
	 * Number of emails in each BCC batch
	 *
	 * @var integer
	 */
	public $BCCBatchSize = 200;

	/**
	 * Enable notify message from server
	 *
	 * @var boolean
	 */
	public $DSN = false;
}
