<?php
/**
 * 购物车模型
 *
 *
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');
class materialModel extends Model {


    public function __construct() {
       parent::__construct('material');
    }

    //素材
    public function del($ids){
        return $this->table('material')->where(array('id'=>array('in',$ids)))->delete();
    }
    public function edit($data){
        $id = $data['id'];
       if($id){//修改
           unset($data['id']);
           $data['addtime'] =TIMESTAMP;
           return $this->table('material')->where(array('id'=>$id))->update($data);
       }else{
           $data['addtime'] =TIMESTAMP;
           $data['download'] =0;
           return $this->table('material')->insert($data);
       }
    }
    public function getMaterialInfo($condition = array(), $field = '*') {
        return $this->table('material')->where($condition)->field($field)->find();
    }
    public function getMaterialList($condition=array(), $page = 10, $filed = '*', $order = '', $key = '') {
        return $this->table('material')->field($filed)->where($condition)->order($order)->page($page)->key($key)->select();
    }
    public function downloadAdd($id){
        if($id){
            return $this->table('material')->where(array('id'=>$id))->setInc('download');
        }
    }

   //标签
    public function editTag($data){
        $id = $data['tag_id'];
        unset($data['tag_id']);
        if(!$id){
            return $this->table('material_tag')->insert($data);
        }
        return $this->table('material_tag')->where(array('tag_id'=>$id))->update($data);
    }
    public function getTagList($condition=array(), $pagesize = '', $filed = '*', $order = '', $key = '') {
        return $this->table('material_tag')->field($filed)->where($condition)->order($order)->page($pagesize)->key($key)->select();
    }
    public function getTagInfo($condition = array(), $field = '*') {
        return $this->table('material_tag')->where($condition)->field($field)->find();
    }
    public function delTag($ids){
        $ids_str ='';
        if(is_array($ids)&&!empty($ids)){
            $ids_str = implode(',',$ids);
        }else{
            $ids_str = $ids = strval($ids);
            $ids = explode(',',$ids);
        }
     //   $id_list = $this->table('material')->where(array('tag_id'=>array('in',$ids_str)))->field('DISTINCT(tag_id) as tag_id')->select();
     //   $exp_ids = array();
      //  foreach($id_list as $val){
      //      $exp_ids[]=$val['tag_id'];
     //   }
     //   $ids = array_diff($ids,$exp_ids);
     //   var_dump($ids);exit;
        return $this->table('material_tag')->where(array('tag_id'=>array('in',implode(',',$ids))))->delete();
    }
}
