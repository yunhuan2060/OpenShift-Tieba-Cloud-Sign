<?php
/**
 * 密码和加密类
 * @copyright (c) Kenvix
 */
class P {

	/**
	 * 对用户密码进行加盐加密
	 * @param string $pwd 密码
	 * @return string 加密的密码
	 */
	public function EncryptPwd($pwd) {
		$s=base64_encode(mcrypt_create_iv(24, MCRYPT_DEV_URANDOM));
		return md5($pwd.USERPW_SALT.$s).$s;
	}
	
	/**
	 * 对加盐密码进行检验
	 * @param string $pw 加盐密码
	 * @param string $pwd 密码原文
	 * @return bool 是否吻合
	 */
	public function CheckPwd($pw,$pwd) {
		$s=substr($pw,32);
		if (strcmp(md5($pwd.USERPW_SALT.$s).$s,$pw) != 0) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * 对数据进行不可逆加密
	 * @param string $pwd 密码
	 * @return string 加密的密码
	 */
	public function pwd($pwd) {
		return eval('return '.option::get('pwdmode').';');
	}

	/**
	 * 对数据进行可逆加密
	 * @param string $str 原文
	 * @param int $cipher 加密算法，留空为默认
	 * @param string $mode  加密模式，留空为默认
	 * @return string 密文或者false表示失败
	 */
	public function encode($str , $cipher = MCRYPT_RIJNDAEL_256, $mode = MCRYPT_MODE_CFB) {
		if (!empty($this->salt)) {
			return base64_encode(mcrypt_encrypt($cipher , $this->salt , $str , $mode));
		} else {
			return $str;
		}
	}

	/**
	 * 解密密文
	 * @param string $str 密文
	 * @param int $cipher 加密算法，留空为默认
	 * @param string $mode  加密模式，留空为默认
	 * @return string|bool 原文或者false表示失败
	 */
	public function decode($str , $cipher = MCRYPT_RIJNDAEL_256, $mode = MCRYPT_MODE_CFB) {
		if (!empty($this->salt)) {
			return mcrypt_decrypt($cipher , $this->salt , base64_decode($str) , $mode);
		} else {
			return $str;
		}
	}
}
