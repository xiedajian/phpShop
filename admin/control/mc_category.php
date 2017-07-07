<?php
/**
 *素材分类
 ***/

defined('InShopNC') or exit('Access Invalid!');
class mc_categoryControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
	/**
	 * 分类管理
	 */
	public function indexOp(){
        $model_category = Model('course/category');
        $condition = array();
        $category_list = $model_category->get_category_menu($condition);
        Tpl::output('class_list',$category_list);
        Tpl::showpage('mc_category.index');
	}

    public function editOp(){
        $model_category = Model('course/category');
        $data = array();
        $condition = array();
        $condition['id'] = intval($_POST['id']);
        switch($_POST['type']){
            case 'state':$data['state'] = intval($_POST['value']);break;
            case 'name':$data['name'] = trim($_POST['value']);break;
            case 'sort':$data['sort'] = intval($_POST['value']);break;
            default:exit(0);
        }
        if($model_category->where($condition)->update($data)){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function delOp(){
        $model_category = Model('course/category');
        $condition = array();
        $condition['id'] = intval($_POST['id']);
        $category = $model_category->where($condition)->find();
        if(!$category){
            echo 0;exit;
        }
        $like = substr($category['category'],0,4*intval($category['level'])).'%';
        if($model_category->where(array('category'=>array('like',$like)))->delete()){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function addOp(){
        $model_category = Model('course/category');
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["name"], "require"=>"true", "message"=>'板块名称不能为空'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $parent_code = '';
                if(preg_match('/^[0-9]{12}$/',$_POST['parent_2'])){
                    $parent_code = $_POST['parent_2'];
                    $level = 3;
                    $like  = substr($_POST['parent_2'],0,8).'____';
                }else if(preg_match('/^[0-9]{12}$/',$_POST['parent_1'])){
                    $parent_code = $_POST['parent_1'];
                    $level = 2;
                    $like = substr($_POST['parent_1'],0,4).'____'.'0000';
                }else{
                    $level = 1;
                    $like = '____00000000';
                }
                //上级分类判断
                if($parent_code){
                    $parent = $model_category->where(array('category'=>$parent_code,'level'=>$level-1))->find();
                    if(empty($parent)){
                        showMessage('参数错误！','index.php?act=mc_category&op=index');
                    }
                }
                //计算category
                $data = array();
                $max = $model_category->where(array('category'=>array('like',$like)))->order('category desc')->find();
                if(!empty($max)){
                    $code = intval($max['category'])+pow(10,4*(3-$level));
                    $data['category'] = substr('0000',0,12-strlen($code)).$code;
                }else{
                    $data['category'] = '000100000000';
                }

                $data['level'] = $level;
                $data['name'] = $_POST['name'];
                $data['sort'] = intval($_POST['sort']);
                $data['state'] = intval($_POST['state']);
                $data['add_time'] = time();
                $data['update_time'] = $data['add_time'];
                if($model_category->insert($data)){
                    showMessage('添加成功','index.php?act=mc_category&op=index');
                }else{
                    showMessage('添加失败','index.php?act=mc_category&op=index');
                }
            }
        }else{
            $list = $model_category->where(array('level'=>1))->select();
            Tpl::output('category',$list);
            Tpl::showpage('mc_category.add');
        }
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
}
