<?php
/**
 *首页板块
 *
 **/

defined('InShopNC') or exit('Access Invalid!');
class mc_blockControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

    /**
     * 板块列表
     */
    public function indexOp(){
        $model_block = Model('course/block');
//        $style_array = $model_block->getStyleList();//板块样式数组
//        Tpl::output('style_array',$style_array);
        $block_list = $model_block->getBlockList(array());
        $style_name = array(
            'red'  =>  '红色',
            'pink'  =>  '粉色',
            'orange'  =>  '橘色',
            'green'  =>  '绿色',
            'blue'  =>  '蓝色',
            'purple'  =>  '紫色',
            'brown'  =>  '褐色',
            'default'  =>  '默认',
        );
        Tpl::output('style_name',$style_name);
        Tpl::output('block_list',$block_list);
        Tpl::showpage('mc_block.index');
    }


    private function _upload_pic($file_name) {
        $pic_name = '';
        if (!empty($file_name)) {
            if (!empty($_FILES[$file_name]['name'])) {//上传图片
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_EDITOR);
                $result = $upload->upfile($file_name);
                if ($result) {
                    $pic_name = $upload->file_name;//加随机数防止浏览器缓存图片
                }
            }
        }
        return $pic_name;
    }


    //板块基本设置
    public function edit_blockOp(){
        $model_block = Model('course/block');
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["id"], "require"=>"true", "message"=>'参数错误'),
                array("input"=>$_POST["name"], "require"=>"true", "message"=>'板块名称不能为空'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $data = array();
                $data['name'] = $_POST['name'];
                $data['style'] = trim($_POST['style_name']);
                $data['sort'] = intval($_POST['sort']);
                $data['state'] = intval($_POST['state']);
                $data['update_time'] = time();
                if($model_block->where(array('id'=>intval($_POST['id'])))->update($data)){
                    showMessage('保存成功','index.php?act=mc_block&op=index');
                }else{
                    showMessage('保存失败','index.php?act=mc_block&op=index');
                }
            }
        }else{
            $id = intval($_GET['id']);
            $val = $model_block->where(array('id'=>$id))->find();
            if(!$val)showMessage('板块不存在，请重试！','index.php?act=mc_block&op=index');
            Tpl::output('val',$val);
            Tpl::showpage('mc_block.edit');
        }
    }

    public function edit_block_contentOp(){
        $model_block = Model('course/block');
        $model_category = Model('course/category');
        //获取所选版块
        if(chksubmit()){
            $id = $_GET["id"]; //获取版块ID
            $meterials = array();
            $meterials_id = $_POST['recommend_goods_id'];
            $meterials_title = $_POST['recommend_goods_name'];
            $meterials_pic = $_POST['recommend_goods_pic'];
            foreach($meterials_id as $key => $v)
            {
                $meterials[$key]['id'] = $v;
                $meterials[$key]['title'] = $meterials_title[$key];
                $meterials[$key]['pic'] = $meterials_pic[$key];
            }
            $meterials = json_encode($meterials); //将素材转换为字符串
            $result_id=$model_block->where(array('id'=>$id))->update(array('content'=>$meterials)); //更新版块内容
            if($result_id){
                showMessage('提交成功','index.php?act=mc_block&op=index');
            }else{
                showMessage('提交失败','index.php?act=mc_block&op=index');
            }
        }
        else{
            $id = intval($_GET["id"]);
            $val = $model_block->where(array('id'=>$id))->find();
            if($val['style']=='red')
                $stylename = '红色';
            else if($val['style']=='pink')
                $stylename = '粉色';
            else if($val['style']=='orange')
                $stylename = '橘色';
            else if($val['style']=='green')
                $stylename = '绿色';
            else if($val['style']=='blue')
                $stylename = '蓝色';
            else if($val['style']=='purple')
                $stylename = '紫色';
            else if($val['style']=='brown')
                $stylename = '褐色';
            else if($val['style']=='default')
                $stylename = '默认';
            if(is_array($val) && !empty($val)){
                $list = $model_category->where(array('level'=>1))->select();
                Tpl::output('category',$list);
            }
            $val['content'] = json_decode($val['content'],true);
            log::record($val[content][0]['title']);
            Tpl::output('val',$val);
            Tpl::output('style',$stylename);
            Tpl::showpage('mc_code.edit');
        }

    }
    /**
     * 版块素材列表
     */
    public function materials_listOp() {
        $model_material = Model('course/material');
        $condition = array();
        $category = $_GET['id'];
        if ($category > 0) {
            $condition['category'] = $category;
        }
        $materials_name = trim($_GET['materials_name']);
        if (!empty($materials_name)) {
            $condition['title'] = array('like','%'.$materials_name.'%');

        }
        $materials_list = $model_material->getlist($condition,'*',7,$order);
        Tpl::output('get',$_GET);
        Tpl::output('show_page',$model_material->showpage());
        Tpl::output('materials_list',$materials_list);
        Tpl::showpage('mc_material.list','null_layout');
    }

    public function add_blockOp(){
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["name"], "require"=>"true", "message"=>'板块名称不能为空'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $data = array();
                $data['name'] = $_POST['name'];
                $data['style'] = trim($_POST['style_name']);
                $data['sort'] = intval($_POST['sort']);
                $data['state'] = intval($_POST['state']);
                $data['add_time'] = time();
                $data['update_time'] = $data['add_time'];
                $model_block = Model('course/block');
                if($model_block->insert($data)){
                    showMessage('添加成功','index.php?act=mc_block&op=index');
                }else{
                    showMessage('添加失败','index.php?act=mc_block&op=index');
                }
            }
        }else{
            Tpl::showpage('mc_block.add');
        }
    }
}
