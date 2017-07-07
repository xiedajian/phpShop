<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/8/2
 * Time: 10:48
 */
class dataControl extends BaseAPPControl{
    public function __construct(){
        parent::__construct();
    }
    public function getDateOP(){
        //检测客户的各个授权商品是否过期

        $this->check_expiredOp();
        //返回数据
        $m  = new Model();
        $r = $m->table('app,user_app')->join('outer left')->on("app.app_id=user_app.app_id AND user_app.uid={$this->currentUser['member_id']}")->select();
        if($r){
            $this->returnJson(1, 'SUCCESS', $r);
        }else{

            $this->returnJson(1001,'获取失败');
        }
    }
    //开启会员宝服务
    public function  hyb_apply_openOP(){
        $m  = new Model();
        $POST = $this->POST;
        $app_id = trim($POST['app_id']);
        $user  = $this->currentUser;
        if(!empty($user)){
            //设置对应的服务状态为1
            $r = $m->execute("insert into 33hao_user_app(uid,app_id,status) VALUES ({$user['member_id']},$app_id,1) on DUPLICATE key UPDATE status=1");
            $this->returnJson(1,'提交成功');
            //    $this->returnJson(2,'提交失败');

        }
    }
    /**
     *  促崔安装添加
     */
    public  function  press_installOP(){
        $m  = new Model();
        $POST = $this->POST;
        $app_id = trim($POST['app_id']);
        $user  = $this->currentUser;
        if(!empty($user)){
            //设置对应的服务状态为2
            $data = array(
                'member_id' => $user['member_id'],
                'app_id' => $app_id,
                'add_time' => time()
            );
            $r  = $m->table('press_install_record')->insert($data);
            if($r){
                $this->returnJson(1,'催促添加成功');
            }else{
                $this->returnJson(2,'催促添加失败');
            }
        }
    }
    /**
     *  检查客户的各个授权商品是否过期
     */
    private function check_expiredOp()
    {
        $licences=Model('licences');
        $licences_company_list=$licences->getlicences_company(intval($this->currentUser['member_id']));
        foreach ($licences_company_list as $k =>$v){
            if($licences_company_list[$k]['app_code']=='KMF'){
                //如果店满分超期
                if($this->daysBetweenOp($licences_company_list[$k]['expire_date'])){
                    $this->expired_manageOp(2);
                }
            }else if($licences_company_list[$k]['app_code']=='DPZS'){
                //如果店铺指数超期
                if($this->daysBetweenOp($licences_company_list[$k]['expire_date'])){
                    $this->expired_manageOp(1);
                }
            }
        }
    }
    /**
     *  判断当前日期与指定日期
     */
    private function daysBetweenOp($expire_date){
        $second1 = strtotime(date('Y-m-d',time()));
        log::record($second1);
        $second2 = strtotime(substr($expire_date,0,10));
        log::record($second2);
        if ($second1>$second2){
            return true;//超期
        }else{
            return false;//没有超期
        }

    }
    /**
     *  过期处理
     */
    private function expired_manageOp($app_id)
    {
        $m  = new Model();
            //设置对应的服务状态为2
        $data = array(
            'uid' => intval($this->currentUser['member_id']),
            'app_id' => $app_id,
        );
       $m->table('user_app')->where($data)->update(array('status'=>2));
    }
}