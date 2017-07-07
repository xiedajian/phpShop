<?php
/*
 * 标签管理
 * */
defined('InShopNC') or exit('Access Invalid!');
class mc_tagControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
	
	//标签显示
	public function indexOp(){
		$model_tag=Model('course/tag');
		//$taglist=$model_tag->where(array('state'=>1))->select();
		if(!empty($_GET['name'])){
			$condition['name']=array('like','%' . trim($_GET['name']) . '%');
		}
		if(!empty($_GET['state'])){
			if($_GET['state']==1){
				$condition['state']=1;
			}else{
				$condition['state']=0;
			}
		}
		
		$taglist=$model_tag->getlist($condition,'*',10,'id desc');
		
		Tpl::output('tag',$taglist);
		Tpl::output('get',$_GET);
		Tpl::output('page',$model_tag->showpage());
		Tpl::showpage('mc_tag.index');
	}
	
	//状态变更单条
	public function tag_delOp(){
		$model_tag=Model('course/tag');
		$id=intval($_GET['id']);
		$state=intval($_GET['state']);
		if($model_tag->where(array('id'=>$id))->update(array('state'=>$state))){
			showMessage('状态变更成功','index.php?act=mc_tag&op=index');
		}else{
			showMessage('状态变更失败','index.php?act=mc_tag&op=index');
		}
	}
	//状态变更多条
	public function stateOp(){
		$model_tag=Model('course/tag');
		$id=$_POST['id'];
		$state=$_POST['state'];
		log::record($state);
		foreach ($id as $v){
			$model_tag->where(array('id'=>$v))->update(array('state'=>$state));
		}
		
		//showMessage('删除成功','index.php?act=mc_material&op=index');
		echo '1';
	}
}

?>