<?php
/**
 *内容管理
 *
 **/
defined('InShopNC') or exit('Access Invalid!');
class mc_materialControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
	
	/**
     * 内容列表
     */
	
	public function indexOp(){
		$model_material = Model('course/material');
		$model_category = Model('course/category');
		$model_tag=Model('course/tag');
		$model_material_tag=Model('course/material_tag');
		$list = $model_category->where(array('level'=>1))->select();  //获取分类
		
// 		if(chksubmit()){
// 			log::record($_POST['title']);
// 		}
		if(!empty($_GET['title'])){
			$condition['title']=array('like','%' . trim($_GET['title']) . '%');
		}
		if(!empty($_GET['parent_3'])){
			$condition['category']=$_GET['parent_3'];
		}else if(!empty($_GET['parent_2'])){
			$condition['category']=array('like',substr($_GET['parent_2'],0,8).'%');
		}else if(!empty($_GET['parent_1'])){
			$condition['category']=array('like',substr($_GET['parent_1'],0,4).'%');
			
		}
		
		if(!empty($_GET['parent_1'])){
			$l2['category']=array('like',substr($_GET['parent_1'],0,4).'%');
			$l2['level']=2;
			$list2=$model_category->where($l2)->select(); //获取第二级
		}
		if(!empty($_GET['parent_2'])){
			$l3['category']=array('like',substr($_GET['parent_2'],0,8).'%');
			$l3['level']=3;
			$list3=$model_category->where($l3)->select();//获取第三级
		}
		
		if(!empty($_GET['type'])){
			$condition['type']=$_GET['type'];
		}
		if(!empty($_GET['price'])){   //是否收费
			if($_GET['price']==1){
				$condition['price']=array('neq',0);
			}else{
				$condition['price']=0;
			}
		}
		if(!empty($_GET['is_jp'])){    //精品课堂
			if($_GET['is_jp']==1){
				$condition['is_jp']=1;
			}else{
				$condition['is_jp']=0;
			}
		}
		//$member_list = $model_member->getMemberList($condition, '*', 10, $order);
		//$materiallist=$model_material->where(array('state'=>1))->select();//获取内容列表
		$condition['state']=1;
		$materiallist=$model_material->getlist($condition,'*',10,'id desc');
		foreach ($materiallist as $k=>$v){
			$category='';  //处理分类
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
			$materiallist[$k]['category']=$category;
			//处理图片
			$pic=json_decode($v['thumb'],true);
			$materiallist[$k]['thumb']=$pic[0];
			//处理tag标签
			$tag='';
			$tag_list=$model_material_tag->where(array('material_id'=>$v['id']))->select();
			if($tag_list){
				foreach ($tag_list as $key=>$l){
					$tag_data=$model_tag->where(array('id'=>$l['tag_id']))->find();
					if($key==0){
						$tag=$tag_data['name'];
					}else{
						$tag.=','.$tag_data['name'];
					}
					
				}
			}
			$materiallist[$k]['tag']=$tag;
			
		}
		
		Tpl::output('materiallist',$materiallist);
		Tpl::output('category',$list);
		Tpl::output('category2',$list2);
		Tpl::output('category3',$list3);
		
		Tpl::output('get',$_GET);
		Tpl::output('page',$model_material->showpage());
		Tpl::showpage('mc_material.index');
	}
	
	//添加内容
	public function addOp(){
		$model_material = Model('course/material');
		$model_category = Model('course/category');
		$model_tag=Model('course/tag');
		$model_material_tag=Model('course/material_tag');
		 if(chksubmit()){
		 	$obj_validate = new Validate();
		 	$obj_validate->validateparam = array(
		 			array("input"=>$_POST["title"], "require"=>"true", "message"=>'标题不为空'),
		 			array("input"=>$_POST["tag"], "require"=>"true", "message"=>'标签不能为空'),
		 	);
		 	$error = $obj_validate->validate();
		 	if ($error != ''){
		 		showMessage($error);
		 	}else{
		 		if(!empty($_POST['parent_3'])){    //分类
		 			$data['category']=$_POST['parent_3'];
		 		}else if(!empty($_POST['parent_2'])){
		 			$data['category']=$_POST['parent_2'];
		 		}else if(!empty($_POST['parent_1'])){
		 			$data['category']=$_POST['parent_1'];
		 		}
		 		
		 		$data['type']=$_POST['type'];//类型
		 		$data['state']=1;//状态
		 		if(!empty($_POST['is_jp'])){
		 			$data['is_jp']=$_POST['is_jp'];   //精品课程
		 		}
		 		
		 		
		 		$data['title']=$_POST['title'];  //标题
		 		$data['remark']=$_POST['remark'];  //摘要
		 		$data['content']=$_POST['content']; //内容
 		 		$data['add_time']=time();   //添加时间
 		 		$browse=0;   
 		 		if(!empty($_POST['browse'])){
 		 			
 		 			foreach ($_POST['browse'] as $v){
 		 			$browse+=$v;	
 		 			}
 		 		}
 		 		$data['browse']=$browse;   //浏览权限
 		 		$data['share']=$_POST['share'];//分享类型
 		 		if(!empty($_POST['pay'])){
 		 			$data['price']=$_POST['price'];  //原价
 		 			$data['promotion_price']=$_POST['promotion_price']; //促销价
 		 			if(!empty($_POST['in_package'])){
 		 				$data['in_package']=$_POST['in_package']; //是否在套餐内
 		 			}else{
 		 				$data['in_package']=0;
 		 			}
 		 			if(!empty($_POST['promotion_time'])){
 		 				$data['promotion_time']=strtotime($_POST['promotion_time']);//促销结束时间
 		 			}
 		 			
 		 			$data['promotion_data']=$_POST['promotion_data'];  //促销词
 		 			$data['operation']=$_POST['operation'];//购买后可执行的操作
 		 		}else{
		 			$data['price']=0;
		 			$data['promotion_data']=0;
		 			$data['in_package']=0;
		 			$data['promotion_time']=0;
		 			$data['promotion_data']="";
		 			$data['operation']=0;
		 		}
 		 		
 		 		//缩略图
 		 		$thumb=array();
 		 		if(!empty($_POST['thumb'])){
 		 			foreach ($_POST['thumb'] as $pic){
 		 				$thumb[]=UPLOAD_SITE_URL.'/'.ATTACH_MATERIAL.'/'.$pic;
 		 			}
 		 		}
 		 		$data['thumb']=json_encode($thumb);
 		 		$data['is_down']=$_POST['is_down'];
 		 		$data['download']=UPLOAD_SITE_URL.'/'.ATTACH_MATERIAL.'/'.$_POST['download'];
 		 		//获取用户信息
 		 		$user=$this->systemLogin();
 		 		$data['publisher']=$user['id'];
 		 		$data['publish_name']=$user['name'];
 		 		$material_id=$model_material->insert($data);
 		 		if($material_id){
 		 			if(!empty($_POST['tag'])){
 		 				foreach ($_POST['tag'] as $val){
 		 					$tag['material_id']=$material_id;
 		 					$tag['tag_id']=$val;
 		 					$model_material_tag->insert($tag);
 		 				}
 		 			}
 		 			showMessage('添加成功','index.php?act=mc_material&op=index');
 		 		}else{
 		 			showMessage('添加失败','index.php?act=mc_material&op=index');
 		 		}
		 	}
		 }else{
		 	
		 	$taglist=$model_tag->where(array('state'=>1))->select();
		 	$list = $model_category->where(array('level'=>1))->select();
		 	
		 	Tpl::output('category',$list);
		 	Tpl::output('tag',$taglist);
		 	Tpl::showpage('mc_material.add');
		 }
	}
	
	//同步添加tag标签
	public function tag_addOp(){
		$model_tag=Model('course/tag');
		$tag=$_REQUEST['tag'];
		
		$tag=explode('，', $tag);
		//$tagid=array();
		foreach ($tag as $v){
			if(!$model_tag->where(array('name'=>$v))->find()){
				$data='';
				$data['name']=$v;
				$data['state']=1;
				$data['add_time']=time();
				$tagid=$model_tag->insert($data);
				$data['id']=$tagid;
				$datalist[]=$data;
			}
		}
		echo json_encode($datalist);
		
	}
	
	//内容图片上传
	public function pic_uploadOp(){
		/**
		 * 上传图片
		 */
		$upload = new UploadFile();
		$upload->set('default_dir',ATTACH_MATERIAL);
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
	
	//下载内容上传
	public function download_uploadOp(){
		$upload = new UploadFile();
		$upload->set('default_dir', ATTACH_MATERIAL);
		$upload->set('is_img',false);
		$upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png','psd','zip','rar'));
		$upload->set('max_size',1024*1024);
		$result = $upload->upfile('download_add');
		if ($result){
			//$_POST['pic'] = $upload->file_name;
			$data['download']=$upload->file_name;
			echo json_encode($data);
		}else {
			echo 'error';exit;
		}
	}
	
	//内容删除单挑
	public function material_delOp(){
		$model_material = Model('course/material');
		$id=intval($_GET['id']);
		if($model_material->where(array('id'=>$id))->update(array('state'=>0))){
			showMessage('删除成功','index.php?act=mc_material&op=index');
		}else{
			showMessage('删除失败','index.php?act=mc_material&op=index');
		}
	}
	//内容删除 多条
	public function delOp(){
		$model_material = Model('course/material');
		$id=$_POST['id'];
		
		foreach ($id as $v){
			$model_material->where(array('id'=>$v))->update(array('state'=>0));
		}
		
		//showMessage('删除成功','index.php?act=mc_material&op=index');
		echo '1';	
	}
	
	//编辑
	public function material_editOp(){
		$model_material = Model('course/material');
		$model_category = Model('course/category');
		$model_tag=Model('course/tag');
		$model_material_tag=Model('course/material_tag');
		 if(chksubmit()){
		 	$obj_validate = new Validate();
		 	$obj_validate->validateparam = array(
		 			array("input"=>$_POST["title"], "require"=>"true", "message"=>'标题不为空'),
		 			array("input"=>$_POST["tag"], "require"=>"true", "message"=>'标签不能为空'),
		 	);
		 	$error = $obj_validate->validate();
		 	if ($error != ''){
		 		showMessage($error);
		 	}else{
		 		if(!empty($_POST['parent_3'])){    //分类
		 			$data['category']=$_POST['parent_3'];
		 		}else if(!empty($_POST['parent_2'])){
		 			$data['category']=$_POST['parent_2'];
		 		}else if(!empty($_POST['parent_1'])){
		 			$data['category']=$_POST['parent_1'];
		 		}
		 		$id=$_POST['id'];
		 		$data['type']=$_POST['type'];//类型
		 		$data['state']=1;//状态
		 		if(!empty($_POST['is_jp'])){
		 			$data['is_jp']=$_POST['is_jp'];   //精品课程
		 		}else{
		 			$data['is_jp']=0;
		 		}
		 		
		 		 
		 		$data['title']=$_POST['title'];  //标题
		 		$data['remark']=$_POST['remark'];  //摘要
		 		$data['content']=$_POST['content']; //内容
		 		//$data['add_time']=time();   //添加时间
		 		$browse=0;
		 		if(!empty($_POST['browse'])){
		 			 
		 			foreach ($_POST['browse'] as $v){
		 				$browse+=$v;
		 			}
		 		}
		 		$data['browse']=$browse;   //浏览权限
		 		$data['share']=$_POST['share'];//分享类型
		 		
		 		if(!empty($_POST['pay'])){
		 			$data['price']=$_POST['price'];  //原价
		 			$data['promotion_price']=$_POST['promotion_price']; //促销价
		 			if(!empty($_POST['in_package'])){
		 				$data['in_package']=$_POST['in_package']; //是否在套餐内
		 			}else{
		 				$data['in_package']=0;
		 			}
		 			if(!empty($_POST['promotion_time'])){
		 				$data['promotion_time']=strtotime($_POST['promotion_time']);//促销结束时间
		 			}else{
		 				$data['promotion_time']=0;
		 			}
		 			
		 			$data['promotion_data']=$_POST['promotion_data'];  //促销词
		 			$data['operation']=$_POST['operation'];//购买后可执行的操作
		 		}else{
		 			$data['price']=0;
		 			$data['promotion_data']=0;
		 			$data['in_package']=0;
		 			$data['promotion_time']=0;
		 			$data['promotion_data']="";
		 			$data['operation']=0;
		 		}
		 		$data['update_time']=time();
		 		//缩略图
		 		
		 		$data['is_down']=$_POST['is_down'];
		 		$data['download']=UPLOAD_SITE_URL.'/'.ATTACH_MATERIAL.'/'.$_POST['download'];
		 		$thumb=array();
		 		if(!empty($_POST['thumb'])){
		 			foreach ($_POST['thumb'] as $pic){
		 				$thumb[]=UPLOAD_SITE_URL.'/'.ATTACH_MATERIAL.'/'.$pic;
		 			}
		 		}
		 		$data['thumb']=json_encode($thumb);
		 		//获取用户信息
		 		$user=$this->systemLogin();
		 		$data['publisher']=$user['id'];
		 		$data['publish_name']=$user['name'];
		 		$material_id=$model_material->where(array('id'=>$id))->update($data);
		 		if($material_id){
		 			$model_material_tag->where(array('material_id'=>$id))->delete();
		 			if(!empty($_POST['tag'])){
		 				foreach ($_POST['tag'] as $val){
		 					$tag['material_id']=$id;
		 					$tag['tag_id']=$val;
		 					$model_material_tag->insert($tag);
		 				}
		 			}
		 			showMessage('修改成功','index.php?act=mc_material&op=index');
		 		}else{
		 			showMessage('修改失败','index.php?act=mc_material&op=index');
		 		}
		 	}
		 }else{
		 	$id=$_GET['id'];
		 	$taglist=$model_tag->where(array('state'=>1))->select();
		 	$list = $model_category->where(array('level'=>1))->select();
		 	$materiallist=$model_material->where(array('id'=>$id))->find();
		 	$materiallist['thumb']=json_decode($materiallist['thumb'],true);
		 //	log::record(substr($materiallist['thumb'][0],46));
		 	$category=$materiallist['category'];
		 	$l3['category']=array('like',substr($category,0,8).'%'); //分类
		 	$l3['level']=3;
		 	$list3=$model_category->where($l3)->select();
		 	
		 	$l2['category']=array('like',substr($category,0,4).'%');
		 	$l2['level']=2;
		 	$list2=$model_category->where($l2)->select();
		 	
		 	//tag标签
		 	$gettag=$model_material_tag->where(array('material_id'=>$id))->select();
		 	$tag=array();
		 	foreach ($gettag as $get){
		 		$tag[]=$get['tag_id'];
		 	}
		 	
		 	Tpl::output('category',$list);
		 	Tpl::output('category3',$list3);
		 	Tpl::output('category2',$list2);
		 	Tpl::output('tag',$taglist);
		 	Tpl::output('tagarray',$tag);
		 	Tpl::output('material',$materiallist);
		 	Tpl::showpage('mc_material.edit');
		 }
	}
	
	//批量修改分类
	public function editcategoryOp(){
		$model_material = Model('course/material');
		$model_category = Model('course/category');
		if(chksubmit()){
			if(!empty($_POST['parent_3'])){    //分类
				$data['category']=$_POST['parent_3'];
			}else if(!empty($_POST['parent_2'])){
				$data['category']=$_POST['parent_2'];
			}else if(!empty($_POST['parent_1'])){
				$data['category']=$_POST['parent_1'];
			}
			$id=$_POST['id'];
			$id=explode(',',$id);
			log::record(json_encode($data));
			foreach ($id as $v){
				$model_material->where(array('id'=>$v))->update($data);
			}
			showMessage('修改成功','index.php?act=mc_material&op=index');
		}else{
			$id=$_GET['id'];
			$id=explode(',',$id);
			$materiallist='';
			foreach ($id as $k=>$v){
				//Log::record($v);
				$material=$model_material->where(array('id'=>$v))->find();
				$category='';  //处理分类
				$code_1 = substr($material['category'],0,4);
				$code_2 = substr($material['category'],4,4);
				$code_3 = substr($material['category'],8,4);
				$get1=$model_category->where(array('category'=>$code_1.'00000000','level'=>'1'))->find();
				$category.=$get1['name'];
				if($code_2!='0000'){
					$get2=$model_category->where(array('category'=>$code_1.$code_2.'0000','level'=>'2'))->find();
					$category.='>'.$get2['name'];
				}
				if($code_3!='0000'){
					$get3=$model_category->where(array('category'=>$material['category'],'level'=>'3'))->find();
					$category.='>'.$get3['name'];
				}
				$material['category']=$category;
				$materiallist[$k]=$material;
				
			}
			$list = $model_category->where(array('level'=>1))->select();
			Tpl::output('category',$list);
			Tpl::output('list',$materiallist);
			Tpl::output('get',$_GET['id']);
			Tpl::showpage('mc_material.editcategory');
		}
		
		
	}
}
?>