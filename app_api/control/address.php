<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/7/15
 * Time: 14:25
 */
class addressControl extends BaseAPPControl{
    public function __construct(){
        parent::__construct();
    }
    //获取收货地址列表
    public function getAddressListOp()
    {
        $member_id=intval( $this->currentUser['member_id']);
        $model_address=Model('address');

        //获取收货地址列表
        $address_list=$model_address->getAddressList(array("member_id"=>$member_id));

        $this->output('成功',0, $address_list);
    }
    //设置默认收货地址
    public function setAddress_firstOp()
    {
        $member_id=intval( $this->currentUser['member_id']);
        $address_id=intval($_GET['address_id']);
        if ($address_id<1){
            $this->output('参数不正确',10,'');
        }
        //取消原默认
        $model_address=Model('address');
        $model_address->editAddress(array('is_default'=>0),array('member_id'=>$member_id,'is_default'=>1));
        //设置默认
        $res=$model_address->editAddress(array("is_default" => 1), array("address_id" => $address_id));
        $this->output($res>0?'修改成功':'修改失败',$res>0?0:1);
    }
    //增加收货地址
    public function addAddressOp()
    {
        $new_address=array();
        $new_address['area_info']=$this->POST['area_info'];
        $new_address['address']=$this->POST['address'];
        $new_address['true_name']=$this->POST['true_name'];
        $new_address['mob_phone']=$this->POST['mob_phone'];
        $new_address['is_default']=$this->POST['is_default'];
        $new_address['member_id']=intval( $this->currentUser['member_id']);
        $address_array=$this->POST['address_array'];
        $new_address['city_id']=Model("area")->getAreaInfo(array('area_code'=>$address_array[1]), $fileds = 'area_id') ;
        if (isset ($address_array[2])){
            $new_address['area_id']=Model("area")->getAreaInfo(array('area_code'=>$address_array[2]), $fileds = 'area_id') ;
        }else{
            $new_address['area_id']=Model("area")->getAreaInfo(array('area_code'=>$address_array[1]), $fileds = 'area_id') ;
        }
        $new_address['member_id']=intval( $this->currentUser['member_id']);
        $model_address=Model('address');

        if ($new_address['is_default']) {
            $model_address->editAddress(array('is_default'=>0),array('member_id'=>$new_address['member_id'],'is_default'=>1));
        }
        $res=Model('address')->addAddress($new_address);
        if ($res){
            $address_list=$model_address->getAddressList(array("member_id"=>intval( $this->currentUser['member_id'])));
            $this->output('添加成功',0,$address_list);
        }
        $this->output('添加失败',1);
        
    }

    //修改某条收货地址
    public function updAddressOp()
    {
        $new_address=array();
        $new_address['address_id']=$this->POST['address_id'];
        $new_address['area_info']=$this->POST['area_info'];
        $new_address['address']=$this->POST['address'];
        $new_address['true_name']=$this->POST['true_name'];
        $new_address['mob_phone']=$this->POST['mob_phone'];
        $new_address['is_default']=$this->POST['is_default'];
        $new_address['member_id']=intval( $this->currentUser['member_id']);
        $address_array=$this->POST['address_array'];
        if (!empty($address_array)){
            $new_address['city_id']=Model("area")->getAreaInfo(array('area_code'=>$address_array[1]), $fileds = 'area_id') ;
            if (isset ($address_array[2])){
                $new_address['area_id']=Model("area")->getAreaInfo(array('area_code'=>$address_array[2]), $fileds = 'area_id') ;
            }else{
                $new_address['area_id']=Model("area")->getAreaInfo(array('area_code'=>$address_array[1]), $fileds = 'area_id') ;
            }
        }
        $model_address=Model('address');
        if ($new_address['is_default']) {
            $model_address->editAddress(array('is_default'=>0),array('member_id'=>$new_address['member_id'],'is_default'=>1));
        }
        $res=$model_address->editAddress($new_address,array('address_id'=>$new_address['address_id']));
        if ($res){
            $address_list=$model_address->getAddressList(array("member_id"=>intval( $this->currentUser['member_id'])));
            $this->output('修改成功',0,$address_list);
        }
        $this->output('修改失败',1);
    }
    //删除收货地址
    public function delAddressOp()
    {
        $address_id=intval($_GET['address_id']);
        if ($address_id<1){
            $this->output('参数不正确',10);
        }
        if (Model('address')->delAddress(array("address_id"=>$address_id))){
            $this->output('删除成功',0);
        }
    }

    //查找城市id对应的省份的id
    public function  getParentOp()
    {
        $parent_array=Model("area")->getCityProvince();
        if (!empty($parent_array)){
            $this->output('成功',0,$parent_array);
        }else{
            $this->output('失败',1);
        }
    }
}
