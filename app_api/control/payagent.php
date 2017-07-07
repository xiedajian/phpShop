<?php


class payagentControl extends BaseAPPControl
{
    
    public function __construct()
    {
        parent::__construct();
    }
    public function get_payagent_listOp()
    {
        $member_id=intval( $this->currentUser['member_id']);
        $res=Model("pay_agent")->getPayAgentList(array('member_id'=>$member_id));
        $this->output('成功',0,$res);
    }
    //设置成默认
    public function set_first_payagentOp()
    {
        $member_id=intval( $this->currentUser['member_id']);
        $payagent_id=intval($_GET['payagent_id']);
        $model=Model("pay_agent");
        //取消原默认
        $model->editPayAgent(array('is_default'=>0),array('member_id'=>$member_id,'is_default'=>1));
        //设置新默认原默认
        $res=$model->editPayAgent(array('is_default'=>1),array('payagent_id'=>$payagent_id));
        $this->output($res>0?'设置成功':'设置失败',$res>0?0:1);
    }
    //添加代付人
    public function add_payagentOp()
    {
        $POST = $this->POST;
        $data=array();
        $data['member_id']=$this->currentUser['member_id'];
        if(!empty($POST)){
            $data['payer_name']= $POST['payer_name'];
            $data['payer_no']= $POST['payer_no'];
        }
        $data['is_default']= '0';
        if (Model('pay_agent')->addPayAgent($data)){
            $res=Model("pay_agent")->getPayAgentList(array('member_id'=>intval( $this->currentUser['member_id'])));
            $this->output('添加成功',0,$res);
        }else{
            $this->output('添加失败',1);
        }
    }
    //更改代付人
    public function edit_payagentOp()
    {
        $POST = $this->POST;
        $data=array();
        $data['member_id']=intval( $this->currentUser['member_id']);
        if(!empty($POST)){
            $payagent_id= $POST['payagent_id'];
            $data['payer_name']= $POST['payer_name'];
            $data['payer_no']= $POST['payer_no'];
        }
        if($data['is_default']){
            Model('pay_agent')->editPayAgent(array('is_default'=>0),array('member_id'=>$data['member_id'],'is_default'=>1));
        }
        if (Model('pay_agent')->editPayAgent($data,array('payagent_id'=>$payagent_id))){
            $res=Model("pay_agent")->getPayAgentList(array('member_id'=>intval( $this->currentUser['member_id'])));
            $this->output('修改成功',0,$res);
        }else{
            $this->output('修改失败',1);
        }
    }
    
    public function del_payagentOp()
    {
        $payagent_id=intval($_GET['payagent_id']);
        $condition=array();
        $condition['payagent_id']=$payagent_id;
        if (Model("pay_agent")->delPayAgent($condition))
        {
            $this->output('删除成功',0);
        }else{
            $this->output('删除失败',1);
        }
    }


}