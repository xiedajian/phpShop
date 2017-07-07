<?php
/*
 * 标签管理
 * */
defined('InShopNC') or exit('Access Invalid!');
class mc_noticeControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
	
	//标签显示
	public function indexOp(){
		$model_notice = Model('course/notice');
		$condition = array();
		$list = $model_notice->getlist($condition,'*',10,'id desc');
		log::record(json_encode($list));
		Tpl::output('notice',$list);
		Tpl::output('page',$model_notice->showpage());
		Tpl::showpage('mc_notice.index');
	}


	public function addOp(){
		if(chksubmit()){
			/*$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["content"], "require"=>"true", "message"=>'发布内容不能为空'),
				array("input"=>$_POST["onshelf_time"], "require"=>"true", "message"=>'计划发布日期不能为空'),
				array("input"=>$_POST["offshelf_time"], "require"=>"true", "message"=>'计划下架日期不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {*/
				$data = array();
				//获取用户信息
				$user=$this->systemLogin();
				$data['content'] = $_POST['content'];
				$data['state'] = 0;
				$data['add_time'] = time();
				$data['update_time'] = $data['add_time'];
				$data['onshelf_time'] = strtotime($_POST['onshelf_time']);
				$data['offshelf_time'] =strtotime($_POST['offshelf_time']);
				$data['publisher']=$user['id'];
				$data['publish_name']=$user['name'];
				$model_notice = Model('course/notice');
				if($model_notice->insert($data)){
					showMessage('添加成功','index.php?act=mc_notice&op=index');
				}else{
					showMessage('添加失败','index.php?act=mc_notice&op=index');
				}

			//}
		}else{
			Tpl::showpage('mc_notice.add');
		}
	}
	public function edit_noticeOp(){
		$model_notice = Model('course/notice');
		if(chksubmit()){
				$id = intval($_POST['id']);
				if(!$id){
					showMessage('保存失败','index.php?act=mc_notice&op=index');
				}
			$data = array();
			//获取用户信息
			$user=$this->systemLogin();
			$data['content'] = $_POST['content'];
			$data['state'] = 0;
			$data['add_time'] = time();
			$data['update_time'] = $data['add_time'];
			$data['onshelf_time'] = strtotime($_POST['onshelf_time']);
			$data['offshelf_time'] =strtotime($_POST['offshelf_time']);
			$data['publisher']=$user['id'];
			$data['publish_name']=$user['name'];
			if($model_notice->where(array('id'=>$id))->update($data)){
				showMessage('保存成功','index.php?act=mc_notice&op=index');
			}else{
				showMessage('保存失败','index.php?act=mc_notice&op=index');
			}

		}else{
			$id = intval($_GET['id']);
			$val = $model_notice->where(array('id'=>$id))->find();
			Tpl::output('val',$val);
			Tpl::showpage('mc_notice.edit');
		}
	}
	
	//状态变更单条
	public function notice_offOp(){
		$model_notice=Model('course/notice');
		$id=intval($_GET['id']);
		$state=intval($_GET['state']);
		if($model_notice->where(array('id'=>$id))->update(array('state'=>$state))){
			showMessage('下架成功','index.php?act=mc_notice&op=index');
		}else{
			showMessage('下架失败','index.php?act=mc_notice&op=index');
		}
	}


	//状态变更多条
	public function notice_offsOp(){
		$model_notice=Model('course/notice');
		$id=$_POST['id'];
		$state=intval($_POST['state']);
		foreach ($id as $v){
			$model_notice->where(array('id'=>$v))->update(array('state'=>$state));
		}
	}
}
?>