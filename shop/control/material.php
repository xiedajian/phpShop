<?php
/**
 *
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');

class materialControl extends BaseHomeControl{

    public function indexOp(){

        $model_material = Model('material');
        $tag_list = $model_material->getTagList();
        Tpl::output('tag_list',$tag_list);
        Tpl::showpage('material');
    }

    public function detailOp(){
        $model_material = Model('material');
        $info = $model_material->getMaterialInfo(array('id'=>intval($_GET['id'])));
        $tag_list = $model_material->getTagList();
        $list = array();
        foreach($tag_list as $val){
            $list[$val['tag_id']] = $val['tag_name'];
        }
        $info['img_url'] = UPLOAD_SITE_URL.DS.MATERIAL_DISPLAY.DS.str_replace('.','_b.',$info['show_filename']);
        $img = @getimagesize(BASE_DATA_PATH.'/upload/'.MATERIAL_DISPLAY.'/'.str_replace('.','_b.',$info['show_filename']));
        Tpl::output('image_type',$img[0]>$img[1]?'w':'h');
        Tpl::output('tag_list',$list);
        Tpl::output('info',$info);
        Tpl::showpage('material_detail');
    }

    public function ajax_materialOp(){
        $condition = array();
        if(intval($_GET['tag_id'])>0){
            $condition['tag_id'] = intval($_GET['tag_id']);
        }
        $pagesize = 15;
        $limit1 = ($_GET['curpage']-1) * $pagesize;
        $limit1 = $limit1>0?$limit1:0;
        $limit2 = $pagesize;
        $model_material = Model('material');

        $sql = 'select * from 33hao_material';
        if($condition['tag_id']){
            $sql.=' where tag_id='.$condition['tag_id'];
        }
        $sql.=' limit '.$limit1.','.$limit2;
        $list = $model_material->query($sql);
		/**
		 * 列表页显示缩略图
		 */
        foreach($list as $k =>$v){
        	$list[$k]['show_filename'] =  str_replace('.','_s.',$v['show_filename']);
        }
        
        $img_path = UPLOAD_SITE_URL.DS.MATERIAL_DISPLAY.DS;
        echo json_encode(array('list'=>$list,'path'=>$img_path));exit;
    }

    public function downloadOp(){
        if(!$_SESSION['member_id']) {
           showMessage('请先登录后下载素材！');
        }
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
        if(!$member_info['org_id']){
            showMessage('请先认证后下载素材！');
        }
        $org = $model_member->getMemberOrg(array('org_id'=>$member_info['org_id']));
        if($org['auth_statu']!=1){
            showMessage('请先通过认证后下载素材！');
        }

        $id = intval($_GET['id']);
        $model_material = Model('material');
        $info = $model_material->getMaterialInfo(array('id'=>$id));
        $this->_outputfile($info);
    }

    private function _outputfile($info){
        $filename = $info['name'];
        $fileurl = BASE_UPLOAD_PATH.DS.MATERIAL.DS.$info['filename'];
        if(file_exists($fileurl)){
            ini_set('memory_limit','256M');
            $ext = substr($info['filename'], strrpos($info['filename'], '.'));
            $model_material = Model('material');
            $model_material->downloadAdd($info['id']);

            $filestream = fopen($fileurl,"r"); // 打开文件
            if($filestream){
                // 输入文件标签
                Header("Content-type: application/octet-stream");
                Header("Accept-Ranges: bytes");
                Header("Accept-Length: ".filesize($fileurl));
                Header("Content-Disposition: attachment; filename=" . $filename.$ext);
                echo fread($filestream,filesize($fileurl));
                fclose($filestream);exit;
            }
            showMessage('素材文件已损坏！');
        }else{
            showMessage('素材不存在！');
        }
    }

}
