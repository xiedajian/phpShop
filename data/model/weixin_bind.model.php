<?php
/**
 * 微信绑定模型
 *
 * 
 *
 *
 */

defined('InShopNC') or exit('Access Invalid!');

class weixin_bindModel extends Model{
    public function __construct(){
        parent::__construct('weixin_bind');
    }

    /**
     * 查询是否绑定微信号
	 */
    public function check_bind($openid) {
        $condition = array(
            'openid'=>$openid
        );
        return $this->where($condition)->find();
    }
    /*
     * 添加绑定
     */
    public function add_bind($data){

        $openid = $data['openid'];
        $member_id = intval($data['member_id']);
        if($openid&&$member_id){
            if(!$this->where(array('member_id'=>$member_id))->count())
                return $this->insert($data);
        }
        return false;
    }
}
