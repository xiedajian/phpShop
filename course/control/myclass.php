<?php
/**
 * 首页
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');

class myclassControl extends BaseControl{

	public  function indexOp(){
        $model_category = Model('course/category');
        $list = $model_category->where(array('level'=>1))->select();//获取类别
        Tpl::output('category',$list);
        Tpl::showpage('mine');
    }
    public function myclassindexOp(){
        $model_category = Model('course/category');
        $list = $model_category->where(array('level'=>1))->select();//获取类别
        $title  = $_GET['title']; //获取标题
        $category = $_GET['category'];
        $tab = $_REQUEST['tab'];
        switch($tab){
            case 'collection':$this->collectionList($title,$category);exit;
            case 'paying':$this->payintList($title,$category);exit;
            case 'paid':$this->paidList($title,$category);exit;
        }
    }
    private  function  collectionList($title,$category){
        $model_category = Model('course/category');
        $user_collection = Model('course/user_collection');
        $condition = array();
        $uid = $_SESSION['member_id']; //获取用户收藏夹
        //获取该用户的收藏夹
        $condition['uid'] = $uid;
        $condition['status'] = 1;
        if(!empty($title)){  //匹配标题
            $condition['title'] =array('like','%'.$title.'%');
        }
        if(!empty($category))
        {
            $condition['category'] =array('like','%'.$category.'%');
        }

        $usercollection_list = $user_collection ->getUserCollectList($condition,'user_collection.id,user_collection.status,material.thumb,material.title,material.category',8);
        foreach($usercollection_list as $k => $v){
            $category = '';
            $code_1 = substr($v['category'],0,4);
            $code_2 = substr($v['category'],4,4);
            $code_3 = substr($v['category'],8,4);
            $get1=$model_category->where(array('category'=>$code_1.'00000000','level'=>'1'))->find();
            $category.=$get1['name'];
            if($code_2!='0000'){
                $get2=$model_category->where(array('category'=>$code_1.$code_2.'0000','level'=>'2'))->find();
                $category.='>'.$get2['name'];
            }
            if($code_3!='0000'){
                $get3=$model_category->where(array('category'=>$v['category'],'level'=>'3'))->find();
                $category.='>'.$get3['name'];
            }
            $usercollection_list[$k]['category']=$category;
            //处理图片
            $pic=json_decode($v['thumb'],true);
            $usercollection_list[$k]['thumb']=$pic[0];
        }
        Tpl::output('page',$user_collection->showpage(2));
        Tpl::output('usercollection_list',$usercollection_list);
        Tpl::output('get',$_GET);
        Tpl::showpage('usercollection.list','null_layout');
    }
    private  function  payintList($title,$category){
        $model_paylist = Model('course/material_order');
        $condition = array();
        $uid = $_SESSION['member_id']; //获取用户收藏夹
        log::record($uid);
        //获取该用户的收藏夹
        $condition['uid'] = $uid;
        $condition['material_order.state'] = 0;
        if(!empty($title)){  //匹配标题
            $condition['title'] =array('like','%'.$title.'%');
        }
        if(!empty($category)){//匹配类别
            $condition['category'] =array('like','%'.$category.'%');
        }
        $paying_list = $model_paylist->getUserpayingList($condition,'material_order.id,material_order.price,
        material_order.state,material_order.counts,material.thumb,material.type,material.title',8);
        foreach($paying_list as $k => $v){
            //处理图片
            $pic=json_decode($v['thumb'],true);
            $paying_list[$k]['thumb']=$pic[0];
            //处理类型
            if($v['type'] ==1){
                $paying_list[$k]['type']='图文';
            }
            elseif($v['type'] ==2){
                $paying_list[$k]['type']='视频';
            }
            elseif($v['type'] ==3){
                $paying_list[$k]['type']='图片';
            }
        }
        Tpl::output('page',$model_paylist->showpage(2));
        Tpl::output('paying_list',$paying_list);
        Tpl::output('get',$_GET);
        Tpl::showpage('user_paying.list','null_layout');
    }
    private  function  paidList($title,$category){
        $model_paidlist = Model('course/material_order');
        $condition = array();
        $uid = $_SESSION['member_id']; //获取用户收藏夹
        //获取该用户的收藏夹
        $condition['uid'] = $uid;
        $condition['material_order.state'] = 2;
        if(!empty($title)){  //匹配标题
            $condition['title'] =array('like','%'.$title.'%');
        }
        if(!empty($category)){
            $condition['title'] =array('like','%'.$category.'%');
        }
        $paid_list = $model_paidlist->getUserpayingList($condition,'material_order.id,material_order.price,
        material_order.state,material_order.counts,material_order.buy_time,material.thumb,material.type,material.title,material.category',8);
        foreach($paid_list as $k => $v){
            $category = '';
            $code_1 = substr($v['category'],0,4);
            $code_2 = substr($v['category'],4,4);
            $code_3 = substr($v['category'],8,4);
            $get1=$model_paidlist->where(array('category'=>$code_1.'00000000','level'=>'1'))->find();
            $category.=$get1['name'];
            if($code_2!='0000'){
                $get2=$model_paidlist->where(array('category'=>$code_1.$code_2.'0000','level'=>'2'))->find();
                $category.='>'.$get2['name'];
            }
            if($code_3!='0000'){
                $get3=$model_paidlist->where(array('category'=>$v['category'],'level'=>'3'))->find();
                $category.='>'.$get3['name'];
            }
            $usercollection_list[$k]['category']=$category;
            //处理图片
            $pic=json_decode($v['thumb'],true);
            $paid_list[$k]['thumb']=$pic[0];
            //处理类型
            if($v['type'] ==1){
                $paid_list[$k]['type']='图文';
            }
            elseif($v['type'] ==2){
                $paid_list[$k]['type']='视频';
            }
            elseif($v['type'] ==3){
                $paid_list[$k]['type']='图片';
            }
        }
        Tpl::output('page',$model_paidlist->showpage(2));
        Tpl::output('paid_list',$paid_list);
        log::record(json_encode($paid_list));
        Tpl::output('get',$_GET);
        Tpl::showpage('user_paid.list','null_layout');
    }
    public function ajax_categoryOp(){
        $code = $_REQUEST['code'];//上级编码
        if(!preg_match('/^[0-9]{12}$/',$code)){
            echo json_encode(array());exit;
        }
        $code_1 = substr($code,0,4);
        $code_2 = substr($code,4,4);
        $code_3 = substr($code,8,4);
        if($code_2=='0000'){
            $level = 1;
        }else if($code_3 == '0000'){
            $level = 2;
        }
        $first_level = array();
        if($level>0&&$level<3){
            $like = substr($code,0,4*$level).'%';
            $model_category = Model('course/category');
            $first_level = $model_category->where(array('level'=>$level+1,'category'=>array('like',$like)))->select();
        }
        echo json_encode($first_level);
    }
    //状态变更单条
    public function usercollection_offOp(){
        $model_user_collection=Model('course/user_collection');
        $id=intval($_GET['id']);
        $state=intval($_GET['state']);
        if($model_user_collection->where(array('id'=>$id))->update(array('status'=>$state))){
            showMessage('取消收藏成功','index.php?act=myclass&op=index&tab=collection');
        }else{
            showDialog('取消收藏失败','','error');
        }
    }

    //状态变更多条
    public function usercollection_offsOp(){
        $model_user_collection=Model('course/user_collection');
        $id=$_POST['id'];
        $state=intval($_POST['status']);
        foreach ($id as $v){
            $model_user_collection->where(array('id'=>$v))->update(array('status'=>$state));
        }
    }

    //删除待付款的记录
    public function paying_delOp(){
        $model_material_order=Model('course/material_order');
        $id=intval($_GET['id']);
        if($model_material_order->where(array('id'=>$id))->delete()){
            showMessage('删除成功','index.php?act=myclass&op=index&tab=collection');
        }else{
            showDialog('删除失败','','error');
        }
    }
}
