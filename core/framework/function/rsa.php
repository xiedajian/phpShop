<?php
/**
 * Created by PhpStorm.
 * User: yuanshian
 * Date: 2015/9/18
 * Time: 11:22
 * Description: RSA证书加密的工具类
 */
class rsa{
	/*
	 * 生成密钥与证书
	 * @param unknown $cerpath  cert证书名称
	 * @param unknown $pfxpath  pfx证书名称
	 * */
	static public function generate_cert_pfx($cerpath,$pfxpath)
	{
		$dn = array(
			"countryName" => 'CN', //所在国家名称
			"stateOrProvinceName" => 'Fujian', //所在省份名称
			"localityName" => 'Fuzhou', //所在城市名称
			"organizationName" => 'iPvp',   //注册人姓名
			"organizationalUnitName" => 'iPvp Corp.', //组织名称
			"commonName" => 'www.ipvp.cn', //公共名称
			"emailAddress" => 'support@ipvp.cn', //邮箱
		);

		$privkeypass = 'P@ssw0rd'; //私钥密码
		$numberofdays = 365;     //有效时长

		//生成证书
		$privkey = openssl_pkey_new();
		$csr = openssl_csr_new($dn, $privkey);
		$sscert = openssl_csr_sign($csr, null, $privkey, $numberofdays);


		openssl_x509_export($sscert, $csrkey); //导出证书$csrkey
		openssl_pkcs12_export($sscert, $privatekey, $privkey, $privkeypass); //导出密钥$privatekey


		//生成证书文件
		$fp = fopen($cerpath, "w");
		fwrite($fp, $csrkey);
		fclose($fp);
		//生成密钥文件
		$fp = fopen($pfxpath, "w");
		fwrite($fp, $privatekey);
		fclose($fp);
	}

	/*
	 * 使用PFX证书加密数据
	 * @param string $data          需要加密的原文数据
	 * @param string $pfxfile       pfx证书名称及路径
	 * @param string $pfxpasswd     读取pfx证书的密码
	 * @param bool $usepublickey    是否用公钥加密
	 * */
	static public function encrypt($data,$pfxfile,$pfxpasswd,$usepublickey=true)
	{
		//$pfxpasswd = 'P@ssw0rd'; //私钥密码

		$fp=fopen($pfxfile,"r");
		$pfx_data=fread($fp,8192);
		fclose($fp);
		$b = openssl_pkcs12_read($pfx_data,$certs,$pfxpasswd);
		/*if(!$b)
			echo "读取寄密钥失败！";
		else
			print_r($certs);*/
		if($usepublickey === true)
			openssl_public_encrypt($data, $crypttext, $certs["cert"]);
		else
			openssl_private_encrypt($data, $crypttext, $certs["pkey"]);

		return base64_encode($crypttext);

		$b = openssl_private_decrypt($crypttext,$newsource,$certs["pkey"]);
		if(!$b)
			echo "解密失败：".$b;
		echo "原文：".$newsource;
	}

	/*
	 * 使用PFX证书解密数据
	 * @param string $data          需要解密的Base64编码的数据（Base64 Encoding Data）
	 * @param string $pfxfile       pfx证书名称及路径
	 * @param string $pfxpasswd     读取pfx证书的密码
	 * @param bool $usepublickey    是否用公钥解密
	 * */
	static public function decrypt($data,$pfxfile,$pfxpasswd,$usepublickey=false)
	{
		//$pfxpasswd = 'P@ssw0rd'; //私钥密码

		$fp=fopen($pfxfile,"r");
		$pfx_data=fread($fp,8192);
		fclose($fp);
		$b = openssl_pkcs12_read($pfx_data,$certs,$pfxpasswd);
		/*if(!$b)
			echo "读取寄密钥失败！";
		else
			print_r($certs);*/
		$data = base64_decode($data);
		if($usepublickey === true)
			openssl_public_decrypt($data, $crypttext, $certs["cert"]);
		else
			openssl_private_decrypt($data, $crypttext, $certs["pkey"]);

		return $crypttext;
	}
}


/*public function encrypt($data,$pfxfile,$pfxpasswd)
{
	//$privkeypass = 'P@ssw0rd'; //私钥密码

	$fp=fopen(BASE_DATA_PATH."/ipvp_server.pfx","r");
	$pfx_data=fread($fp,8192);
	fclose($fp);
	$b = openssl_pkcs12_read($pfx_data,$certs,$privkeypass);
	if(!$b)
		echo "读取寄密钥失败！";
	else
		print_r($certs);
	openssl_public_encrypt($data, $crypttext, $certs["cert"]);
	echo "密文：".base64_encode($crypttext);

	$b = openssl_private_decrypt($crypttext,$newsource,$certs["pkey"]);
	if(!$b)
		echo "解密失败：".$b;
	echo "原文：".$newsource;
}*/
