<?php
/**
 * 首页板块模型
 */
defined('InShopNC') or exit('Access Invalid!');

class blockModel extends Model {
    public function __construct() {
        parent::__construct('block','mc_');
    }


    //后去板块列表
    public function get_block_list($condition =array()){
        return $this->where($condition)->order('sort asc')->select();
    }

    //修改板块内容
    public function edit_block_content($id,$data){
        $title = $data['title'];
        $material_ids = $data['ids'];//数组

        $content = array();
        $content['title'] = $title;
        $content['ids'] = $material_ids;

        $condition = array();
        $condition['id'] = $id;
        $data = array();
        $data['content'] = serialize($content);
        return $this->where($condition)->update($data);
    }

    //获取板块内容，包含素材内容
    public function get_block_content($block){
        $content = unserialize($block['content']);
        $material_ids = $content['ids'];
        $material_sort_list =array();
        if(is_array($material_ids)&&!empty($material_ids)){
            $material_list = $this->table('material')->where(array('id'=>array('in',$material_ids)))->select();
            foreach($material_list as $val){
                $arr[$val['id']] = $val;
            }
            foreach($material_ids as $id){
                $material_sort_list[$id] = $arr[$id];
            }
        }

        $ret = array();
        $ret['title'] = $content['title'];
        $ret['material_list'] = $material_sort_list =array();

        return $ret;
    }
}