<?php
/**
 * Created by PhpStorm.
 * User: yuanshian
 * Date: 2015/9/17
 * Time: 20:15
 */
class aes
{
	static public $mode = MCRYPT_MODE_NOFB;

	/*
	 * 生成AES256专用密钥
	 * @param unknown $length
	 * */
	static public function generateKey($length = 32)
	{
		if (!in_array($length, array(16, 24, 32)))
			return False;

		$str = '';
		for ($i = 0; $i < $length; $i++) {
			$str .= chr(rand(33, 126));
		}
		return $str;
	}

	/*
	 * 用AES256加密数据
	 * @param unknown $data
	 * @param unknown $key
	 * */
	static public function encrypt($data, $key)
	{
		if (strlen($key) > 32 || !$key)
			return trigger_error('key too large or key is empty.', E_USER_WARNING) && False;

		$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, self::$mode);
		$iv = mcrypt_create_iv($ivSize, (substr(PHP_OS, 0, 1) == 'W' ? MCRYPT_RAND : MCRYPT_DEV_URANDOM));
		$encryptedData = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, self::$mode, $iv);
		$encryptedData = $iv . $encryptedData;

		return base64_encode($encryptedData);
	}

	/*
	 * 用AES256解密数据
	 * @param unknown $data
	 * @param unknown $key
	 * */
	static public function decrypt($data, $key)
	{
		if (strlen($key) > 32 || !$key)
			return trigger_error('key too large or key is empty.', E_USER_WARNING) && False;
		$data = base64_decode($data);
		if (!$data)
			return False;
		$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, self::$mode);
		$iv = substr($data, 0, $ivSize);
		$data = substr($data, $ivSize);
		$decryptData = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $data, self::$mode, $iv);
		return $decryptData;
	}

	/*
	 * 用AES256，CBC模式加密数据
	 * @param unknown $data 加密数据
	 * @param unknown $key  加密密码
	 * @param unknown $iv   初始向量
	 * */
	static public function encrypt_cbc($data, $key, $iv)
	{
		/*$key = "1234567812345678";
		$iv = "1234567812345678";
		$data = "Test Sddtring";*/

		//加密
		$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_CBC, $iv);
		return base64_encode($encrypted);
	}

	/*
	 * 用AES256，CBC模式解密数据
	 * @param unknown $data 解密数据
	 * @param unknown $key  解密密码
	 * @param unknown $iv   初始向量
	 * */
	static public function decrypt_cbc($data, $key, $iv)
	{
		//解密
		$encryptedData = base64_decode($data);
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encryptedData, MCRYPT_MODE_CBC, $iv);
	}
}
/*<?php
密钥长度限制
class aes {

	// CRYPTO_CIPHER_BLOCK_SIZE 32

	private $_secret_key = 'default_secret_key';

	public function setKey($key) {
		$this->_secret_key = $key;
	}

	public function encode($data) {
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'',MCRYPT_MODE_CBC,'');
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
		mcrypt_generic_init($td,$this->_secret_key,$iv);
		$encrypted = mcrypt_generic($td,$data);
		mcrypt_generic_deinit($td);

		return $iv . $encrypted;
	}

	public function decode($data) {
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'',MCRYPT_MODE_CBC,'');
		$iv = mb_substr($data,0,32,'latin1');
		mcrypt_generic_init($td,$this->_secret_key,$iv);
		$data = mb_substr($data,32,mb_strlen($data,'latin1'),'latin1');
		$data = mdecrypt_generic($td,$data);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		return trim($data);
	}
}

$aes = new aes();
$aes->setKey('key');

// 加密
$string = $aes->encode('string');
// 解密
$aes->decode($string);

*/
?>