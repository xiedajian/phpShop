<?php
/**
 * cms首页
 *
 *
 *
 
 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');
class indexControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }

    /**
     * 首页
     */
	public function indexOp() {
        $model_mb_special = Model('mb_special');
        $data = $model_mb_special->getMbSpecialIndex();
        $xianshi_list=Model('p_xianshi_goods')->getXianshiGoodsCommendList(5);
	    foreach($xianshi_list as $i=>$item){
            $xianshi_list[$i]['time_down']=intval($item['end_time'])-TIMESTAMP;
        }
        //限时抢购
        array_splice($data,1,0,array(array('xianshi'=>array('item'=>$xianshi_list))));
        //是否登录
        $show_price = false;
        $member_id=$_SESSION['member_id'];
        if(empty($member_id)){
          $islogin=0;
        }else{

              $islogin=1;
              $model_member = Model('member');
              $member_info = $model_member->getMemberInfoByID($member_id);
              $org_info=$model_member->getMemberOrg(array('org_id'=>$member_info['org_id']),"org_name,auth_statu");
              $member_info['auth_statu']=$org_info['auth_statu'];
                if($member_info['auth_statu']==1){
                    $islogin =2;
                }

        }
        array_unshift($data,array('islogin'=>$islogin));
        $this->_output_special($data, $_GET['type']);
	}

    /**
     * 专题
     */
	public function specialOp() {
        $model_mb_special = Model('mb_special'); 
        $data = $model_mb_special->getMbSpecialItemUsableListByID($_GET['special_id']);
        $this->_output_special($data, $_GET['type'], $_GET['special_id']);
	}

    public function special_descOp(){
          $model_mb_special = Model('mb_special'); 
          $mb_special=$model_mb_special->getMbSpecialById($_GET['special_id']);
          exit($mb_special['special_desc']);
        }
    /**
     * 输出专题
     */
    private function _output_special($data, $type = 'json', $special_id = 0) {
        $model_special = Model('mb_special');
        if($_GET['type'] == 'html') {
            $html_path = $model_special->getMbSpecialHtmlPath($special_id);
            if(!is_file($html_path)) {
                ob_start();
                Tpl::output('list', $data);
                Tpl::showpage('mb_special');
                file_put_contents($html_path, ob_get_clean());
            }
            header('Location: ' . $model_special->getMbSpecialHtmlUrl($special_id));
            die;
        } else {
			if(empty($data))
			{
				$data=array();
			}
			//$data['site_mobile_logo']=array(C('site_mobile_logo'));
			//print_r($data);
            output_data($data);
        }
    }

    /**
     * android客户端版本号
     */
    public function apk_versionOp() {
		$version = C('mobile_apk_version');
		$url = C('mobile_apk');
        if(empty($version)) {
           $version = '';
        }
        if(empty($url)) {
            $url = '';
        }

        output_data(array('version' => $version, 'url' => $url));
    }
}
