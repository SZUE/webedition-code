<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Mail
 * @subpackage Transport
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Class for sending eMails via the PHP internal mail() function
 *
 * @category   Zend
 * @package    Mail
 * @subpackage Transport
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class we_mail_TransportSendmail extends we_mail_TransportAbstract{
	/**
	 * Subject
	 * @var string
	 * @access public
	 */
	public $subject = null;

	/**
	 * Config options for sendmail parameters
	 *
	 * @var string
	 */
	public $parameters;

	/**
	 * EOL character string
	 * @var string
	 * @access public
	 */
	public $EOL = PHP_EOL;

	/**
	 * error information
	 * @var string
	 */
	protected $_errstr;

	/**
	 * Constructor.
	 *
	 * @param  string|array|Config $parameters OPTIONAL (Default: null)
	 * @return void
	 */
	public function __construct($parameters = null){
		if(is_array($parameters)){
			$parameters = implode(' ', $parameters);
		}

		$this->parameters = $parameters;
	}

	/**
	 * Send mail using PHP native mail()
	 *
	 * @access public
	 * @return void
	 * @throws Mail_Transport_Exception if parameters is set
	 *         but not a string
	 * @throws Mail_Transport_Exception on mail() failure
	 */
	public function _sendMail(){
		if($this->parameters === null){
			set_error_handler([$this, '_handleMailErrors']);
			$result = mail(
				$this->recipients, $this->_mail->getSubject(), $this->body, $this->header);
			restore_error_handler();
		} else {
			if(!is_string($this->parameters)){
				throw new we_mail_exception('Parameters were set but are not a string'
				);
			}

			set_error_handler([$this, '_handleMailErrors']);
			$result = mail(
				$this->recipients, $this->_mail->getSubject(), $this->body, $this->header, $this->parameters);
			restore_error_handler();
		}

		if($this->_errstr !== null || !$result){
			throw new we_mail_exception('Unable to send mail. ' . $this->_errstr);
		}
	}

	/**
	 * Format and fix headers
	 *
	 * mail() uses its $to and $subject arguments to set the To: and Subject:
	 * headers, respectively. This method strips those out as a sanity check to
	 * prevent duplicate header entries.
	 *
	 * @access  protected
	 * @param   array $headers
	 * @return  void
	 * @throws  Mail_Transport_Exception
	 */
	protected function _prepareHeaders($headers){
		if(!$this->_mail){
			throw new we_mail_exception('_prepareHeaders requires a registered Mail object');
		}

		// mail() uses its $to parameter to set the To: header, and the $subject
		// parameter to set the Subject: header. We need to strip them out.
		if(0 === strpos(PHP_OS, 'WIN')){
			// If the current recipients list is empty, throw an error
			if(empty($this->recipients)){
				throw new we_mail_exception('Missing To addresses');
			}
		} else {
			// All others, simply grab the recipients and unset the To: header
			if(!isset($headers['To'])){
				throw new we_mail_exception('Missing To header');
			}

			unset($headers['To']['append']);
			$this->recipients = implode(',', $headers['To']);
		}

		// Remove recipient header
		unset($headers['To']);

		// Remove subject header, if present
		if(isset($headers['Subject'])){
			unset($headers['Subject']);
		}

		// Prepare headers
		parent::_prepareHeaders($headers);

		// Fix issue with empty blank line ontop when using Sendmail Trnasport
		$this->header = rtrim($this->header);
	}

	/**
	 * Temporary error handler for PHP native mail().
	 *
	 * @param int    $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param string $errline
	 * @param array  $errcontext
	 * @return true
	 */
	public function _handleMailErrors($errno, $errstr, $errfile = null, $errline = null, array $errcontext = null){
		$this->_errstr = $errstr;
		return true;
	}

}