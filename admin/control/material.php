<?php
/**
 *素材管理
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class materialControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

    public function indexOp(){
        if ($_POST['form_submit'] == 'ok'){
            //删除素材
            if (is_array($_POST['del_id']) && !empty($_POST['del_id'])){
                $ids = array();
                foreach ($_POST['del_id'] as $k => $v){
                    $v = intval($v);
                    if($v){
                        $ids[] = $v;
                    }
                }
                if($ids){
                    $model_material = Model('material');
                    if($model_material->del(implode(',',$ids))){
                        showMessage('删除成功');
                    }else{
                        showMessage('删除失败');
                    }
                }
            }else {
                showMessage('参数错误');
            }
        }
        //列表展示
        $condition = array();
        //modify by xwt 2016.3.29
        $search_txt = trim($_GET['search_txt']);
        $model_material = Model('material');
        $tags = $model_material->getTagList(array(),'','*','','tag_id');
        if(intval($_GET['search_type'])==2){
            $tempCondition['tag_name'] = array('like', "%$search_txt%");
            $tags_id = array();
            foreach ($tags as $t){
                if(mb_stristr($t['tag_name'], $search_txt)){
                    array_push($tags_id, $t['tag_id']);
                }
            }
            $condition['tag_id'] = array('in', $tags_id);
     //       $tag_id = intval($_GET['tag']);
            //      $condition['tag_id'] = $tag_id;
        }else if(intval($_GET['search_type'])==1){
            //modify by xwt 2016.3.29
            $condition['name'] = array('like',"%$search_txt%");
        }
        if(count($condition)>0){
            Tpl::output('do_search',1);
        }
        $list = $model_material->getMaterialList($condition,10);

        $tags = $model_material->getTagList(array(),'','*','','tag_id');
     //  var_dump($condition,$list);exit;
        Tpl::output('material_list',$list);
        Tpl::output('tag',$tags);
        Tpl::output('page',$model_material->showpage());
        Tpl::showpage('material.index');
    }
    public function delOp(){
        $id = intval($_GET['id']);
        $model_material = Model('material');
        if($model_material->del($id)){
            showMessage('删除成功');
        }else{
            showMessage('删除失败');
        }
    }



    public function uploadOp(){
        $upload = new UploadFile();
        if(strpos($_POST['name'],'show')!==false){//展示文件
            ini_set('memory_limit','256M');
            $upload->set('default_dir', MATERIAL_DISPLAY.DS. $upload->getSysSetPath());
            $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
            $upload->set('max_size',C('image_max_filesize'));
            $img = @getimagesize($_FILES[$_POST['name']]['tmp_name']);
            if($img[0]>$img[1]){//横向图片
                $upload->set('thumb_width', '1024,220');
                $upload->set('thumb_height', '10000,10000');
            }else{
                $upload->set('thumb_width', '10000,220');
                $upload->set('thumb_height', '800,10000');
            }
            $upload->set('thumb_',true);	//是否使用缩略名
            $upload->set('use_old_name' , true);	//是否使用之前的名称
            $upload->set('thumb_ext', '_b,_s');
            $upload->set('ifremove' , true);	
        }else{
            $upload->set('default_dir', MATERIAL.DS. $upload->getSysSetPath());
            $upload->set('is_img',false);
            $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png','psd','zip','rar'));
            $upload->set('max_size',100*1024);
        }

        $result = $upload->upfile($_POST['name']);
        if (!$result) {
            exit(json_encode(array("error"=>$upload->error)));
        }
        $img_name = $filename = $upload->get('file_name');
        if(strpos($_POST['name'],'show')!==false) {//展示文件
            $img_name = str_replace('.','_s.',$filename);
        }
        exit(json_encode(array("img_path"=>UPLOAD_SITE_URL.DS.$upload->get('default_dir').$img_name,"img_name"=>$img_name,"filename"=>$filename)));
    }
    //素材上传、修改
    public function editOp(){
        if ($_POST['form_submit'] == 'ok'){
            $error ='';
            $data =array();
            $data['id'] = intval($_POST['id']);
            $data['brief'] = $_POST['brief'];
            do{
                if($_POST['show_file_img']){
                    $data['show_filename'] =$_POST['show_file_img'];
                }else{
                    $error ='请上传展示图片';break;
                }
                if($_POST['material_img']){
                    $data['filename'] =$_POST['material_img'];
                }else{
                    $error ='请上传素材';break;
                }
                if($_POST['name']){
                    $data['name'] =$_POST['name'];
                }else{
                    $error ='标题不能为空';break;
                }
                if($_POST['tag_id']){
                    $data['tag_id'] =$_POST['tag_id'];
                }else{
                    $error ='标题不能为空';break;
                }
            }while(false);
            if($error){
                showMessage($error);
            }

            $model_material = Model('material');
            if($model_material->edit($data)){
                showMessage('上传成功','index.php?act=material&op=index');
            }else{
                showMessage('上传失败');
            }

        }else{
            $model_material = Model('material');
            $tags = $model_material->getTagList();
            Tpl::output('tag',$tags);
            if(intval($_GET['id'])){
                $material_info = $model_material->getMaterialInfo(array('id'=>intval($_GET['id'])));
                $img_name = str_replace('.','_s.',$material_info['show_filename']);
                $material_info['show_file_url'] = UPLOAD_SITE_URL.DS.MATERIAL_DISPLAY.DS.$img_name;
                Tpl::output('material_info',$material_info);
            }
            Tpl::showpage('material.upload');
        }
    }

    /**标签**/
    public function tagOp(){
        if ($_POST['form_submit'] == 'ok'){
            //删除标签
            if (is_array($_POST['del_id']) && !empty($_POST['del_id'])){
                $ids = array();
                foreach ($_POST['del_id'] as $k => $v){
                    $v = intval($v);
                    if($v){
                        $ids[] = $v;
                    }
                }
                if($ids){
                    $model_material = Model('material');
                    if($model_material->delTag(implode(',',$ids))){
                        showMessage('删除成功');
                    }else{
                        showMessage('删除失败');
                    }
                }
            }else {
                showMessage('参数错误');
            }
        }

        $condition = array();
        if($_GET['tag_name']){
            $condition['tag_name'] = array('like', "%{$_GET['tag_name']}%");
        }
        if(count($condition)>0){
            Tpl::output('do_search',1);
        }
        $model_material = Model('material');
        $list = $model_material->getTagList($condition,10);
        Tpl::output('tag_list',$list);
        Tpl::output('page',$model_material->showpage());
        Tpl::showpage('material.tag');
    }
    public function deltagOp(){
        $id = intval($_GET['id']);
        $model_material = Model('material');
        if($model_material->delTag($id)){
            showMessage('删除成功');
        }else{
            showMessage('删除失败');
        }
    }

    public function editTagOp(){
        if(chksubmit()){
            $data = array();
            $data['tag_id'] = $_POST['tag_id'];
            $data['tag_name'] = $_POST['tag_name'];
            $data['add_time'] = TIMESTAMP;
            $model_material = Model('material');
            $op = $_POST['tag_id']?'修改':'添加';
            if($model_material->editTag($data)){
                showDialog($op.'成功', 'reload', 'succ');
            }else{
                showDialog($op.'失败', 'reload', 'succ');
            }
        }

        $id =intval($_GET['id']);
        if($id){
            $model_material = Model('material');
            $tag_info = $model_material->getTagInfo(array('tag_id'=>$id));
            Tpl::output('tag_info',$tag_info);
        }
        Tpl::showpage('material.tag.edit','null_layout');

    }


}
