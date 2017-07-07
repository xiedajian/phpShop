<?php
/**
 *素材分类
 ***/

defined('InShopNC') or exit('Access Invalid!');
class mc_menuControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
    public function indexOp(){
        $model_menu = Model('course/top_menu');
        $list = $model_menu->get_top_menu(array());
        Tpl::output('menu_list',$list);
        Tpl::output('top_link',$this->sublink($this->links,'index'));
        Tpl::showpage('mc_menu.index');
    }
    public function editOp(){
        $model_menu = Model('course/top_menu');
        $data = array();
        $condition = array();
        $condition['id'] = intval($_POST['id']);
        switch($_POST['type']){
            case 'sort':$data['sort'] = intval($_POST['value']);break;
            default:exit(0);
        }
        if($model_menu->where($condition)->update($data)){
            echo 1;
        }else{
            echo 0;
        }
    }
    public function edit_menuOp(){
        $model_menu = Model('course/top_menu');
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["title"], "require"=>"true", "message"=>'标题不能为空'),
                array("input"=>$_POST["link"], "require"=>"true", "message"=>'链接地址不能为空'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $id = intval($_POST['id']);
                if(!$id){
                    showMessage('保存失败','index.php?act=mc_menu&op=index');
                }
                $data = array();
                $data['title'] = $_POST['title'];
                $data['link'] = $_POST['link'];
                $data['sort'] = intval($_POST['sort']);
                $data['state'] = intval($_POST['state']);
                $data['blank'] = intval($_POST['blank']);
                $data['update_time'] = $data['add_time'];
                $thumb=array();
                if(!empty($_POST['thumb'])){
                	foreach ($_POST['thumb'] as $pic){
                		$thumb[]=UPLOAD_SITE_URL.'/'.ATTACH_TOPMENU.'/'.$pic;
                	}
                }
                if(!empty($_POST['thumb_link'])){
                	//                 	foreach ($_POST['thumb_link'] as $link){
                	//                 		log::record($link);
                	//                 	}
                	$data['thumb_link']=serialize($_POST['thumb_link']);
                }else{
                	$data['thumb_link']='';
                }
                $data['thumb']=serialize($thumb);
                if($model_menu->where(array('id'=>$id))->update($data)){
                    showMessage('保存成功','index.php?act=mc_menu&op=index');
                }else{
                    showMessage('保存失败','index.php?act=mc_menu&op=index');
                }
            }
        }else{
            $id = intval($_GET['id']);
            $val = $model_menu->where(array('id'=>$id))->find();
            $val['thumb']=unserialize($val['thumb']);
            $val['thumb_link']=unserialize($val['thumb_link']);
            Tpl::output('val',$val);
            Tpl::output('top_link',$this->sublink($this->links,'edit_menu'));
            Tpl::showpage('mc_menu.edit');
        }
    }

    public function addOp(){
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["title"], "require"=>"true", "message"=>'标题不能为空'),
                array("input"=>$_POST["link"], "require"=>"true", "message"=>'链接地址不能为空'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $data = array();
                $data['title'] = $_POST['title'];
                $data['link'] = $_POST['link'];
                $data['sort'] = intval($_POST['sort']);
                $data['state'] = intval($_POST['state']);
                $data['blank'] = intval($_POST['blank']);
                $data['add_time'] = time();
                $data['update_time'] = $data['add_time'];
                
                $thumb=array();
                if(!empty($_POST['thumb'])){
                	foreach ($_POST['thumb'] as $pic){
                		$thumb[]=UPLOAD_SITE_URL.'/'.ATTACH_TOPMENU.'/'.$pic;
                	}
                }
                if(!empty($_POST['thumb_link'])){
//                 	foreach ($_POST['thumb_link'] as $link){
//                 		log::record($link);
//                 	}
                	$data['thumb_link']=serialize($_POST['thumb_link']);
                }
                $data['thumb']=serialize($thumb);
                $model_menu = Model('course/top_menu');
                if($model_menu->insert($data)){
                    showMessage('添加成功','index.php?act=mc_menu&op=index');
                }else{
                    showMessage('添加失败','index.php?act=mc_menu&op=index');
                }
            }
        }else{
            Tpl::output('top_link',$this->sublink($this->links,'add'));
            Tpl::showpage('mc_menu.add');
        }
    }

    public function delOp(){
        $model_category = Model('course/top_menu');
        $condition = array();
        $condition['id'] = intval($_POST['id']);
        if($model_category->where($condition)->delete()){
            echo 1;
        }else{
            echo 0;
        }
    }

    private $links = array(
        array('url'=>'act=mc_menu&op=add','lang'=>'nc_new')
    );
    
    //内容图片上传
    public function pic_uploadOp(){
    	/**
    	 * 上传图片
    	 */
    	$upload = new UploadFile();
    	$upload->set('default_dir',ATTACH_TOPMENU);
    	$result = $upload->upfile('fileupload');
    	if ($result){
    		//$_POST['pic'] = $upload->file_name;
    		$data['pic']=$upload->file_name;
    		$pic=explode('.', $data['pic']);
    		$data['id']=$pic[0];
    		//$data['id']='111';
    		echo json_encode($data);
    	}else {
    		echo 'error';exit;
    	}
    	
    }
}
