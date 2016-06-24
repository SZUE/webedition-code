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
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @category   Zend
 * @package    Mail
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class we_mail_exception extends Exception{
	/**
	 * @var null|Exception
	 */
	private $_previous = null;

	/**
	 * Construct the exception
	 *
	 * @param  string $msg
	 * @param  int $code
	 * @param  Exception $previous
	 * @return void
	 */
	public function __construct($msg = '', $code = 0, Exception $previous = null){
		parent::__construct($msg, (int) $code, $previous);
	}

	/**
	 * Overloading
	 *
	 * For PHP < 5.3.0, provides access to the getPrevious() method.
	 *
	 * @param  string $method
	 * @param  array $args
	 * @return mixed
	 */
	public function __call($method, array $args){
		if('getprevious' == strtolower($method)){
			return $this->_getPrevious();
		}
		return null;
	}

	/**
	 * Returns previous Exception
	 *
	 * @return Exception|null
	 */
	protected function _getPrevious(){
		return $this->_previous;
	}

}
