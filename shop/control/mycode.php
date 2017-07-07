<?php
/**
 * Session 验证码
 ***/


defined('InShopNC') or exit('Access Invalid!');

class mycodeControl{
	public function __construct(){
	}

	/**
	 * 产生验证码,根据hash参数
	 */
	public function makecodeOp(){
        $hash = $_REQUEST['hash'];
        if(empty($hash)){
            exit('hash is empty!');
        }
        $v_code = rand(1000,9999);
        if(!_set_code($hash,$v_code)){
            exit('error!');
        }

		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");

		$code = new seccode();
		$code->code = strval($v_code);
		$code->width = 90;
		$code->height = 26;
		$code->background = 1;
		$code->adulterate = 1;
		$code->scatter = '';
		$code->color = 1;
		$code->size = 0;
		$code->shadow = 1;
		$code->animator = 0;
		$code->datapath =  BASE_DATA_PATH.'/resource/seccode/';
		$code->display();
	}

    //AJAX验证验证码是否正确
	public function checkOp(){
        $hash = $_GET['hash'];
        $code = $_GET['code'];

        if (_check_code($hash,$code)){
			exit('true');
		}else{
			exit('false');
		}
	}

    //ipvp手机端获取hash值
    public function get_hashOp(){
        $param = $_REQUEST['param'];
        list($act,$op) = explode(',',$param);
        echo _getNchash($act,$op);
    }

}

?>
