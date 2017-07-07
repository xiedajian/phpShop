<?php
class send_code_recordModel extends Model {

	public function __construct(){
		parent::__construct('send_code_record');
	}
	

    //添加短信发送请求记录
    public function add_record($mobile,$ip,$url){
        $data = array();
        $data['request_url'] = $url;
        $data['mobile'] = $mobile;
        $data['ip'] = $ip;
        $data['add_time'] = TIMESTAMP;
        $data['send'] = 0;//未发送
        return $this->table('send_code_record')->insert($data);
    }

    //判断是否可以发送
    public function can_send($mobile,$ip){
        $ret = $this->ip_black_list($ip);
        if(!$ret){
            return $ret;
        }

        $ret = $this->check_send_by_mobile($mobile);
        if(!$ret){
            return $ret;
        }
        $ret = $this->check_send_by_ip($ip);
        if(!$ret){
            return $ret;
        }
        return $ret;
    }

    //验证手机号是否可以发送短信
    public function check_send_by_mobile($mobile){
        if(empty($mobile)){
            return false;
        }
        //发送限制
        $one_minutes_send_limit = 1;
        $thirty_minutest_send_limit = 2;
        $one_day_send_limit = 10;

        $condition = array();
        $condition['mobile'] = $mobile;
        $condition['send'] = 1;

        //1分钟发送次数
        $condition['add_time'] = array('gt',TIMESTAMP-60);
        $times = $this->table('send_code_record')->where($condition)->count();
        if($times>=$one_minutes_send_limit){
            return false;
        }
        //30分钟发送次数
        $condition['add_time'] = array('gt',TIMESTAMP-1800);
        $times = $this->table('send_code_record')->where($condition)->count();
        if($times>=$thirty_minutest_send_limit){
            return false;
        }
        //1天内发送次数
        $condition['add_time'] = array('gt',TIMESTAMP-3600*24);
        $times = $this->table('send_code_record')->where($condition)->count();
        if($times>=$one_day_send_limit){
            return false;
        }
        return true;
    }
    //验证ip号是否可以发送短信
    public function check_send_by_ip($ip){
        if(empty($ip)){
            return true;
        }
        //发送限制
        $one_hour_send_limit = 10;
        $one_day_send_limit = 20;

        $condition = array();
        $condition['ip'] = $ip;
        $condition['send'] = 1;
        //1小时内发送次数
        $condition['add_time'] = array('gt',TIMESTAMP-3600);
        $times = $this->table('send_code_record')->where($condition)->count();
        if($times>=$one_hour_send_limit){
            return false;
        }
        //1天内发送次数
        $condition['add_time'] = array('gt',TIMESTAMP-3600*24);
        $times = $this->table('send_code_record')->where($condition)->count();
        if($times>=$one_day_send_limit){
            return false;
        }
        return true;
    }

    //发送成功修改发送记录状态
    public function send_succ($id){
        $condition = array();
        $condition['id'] = intval($id);
        $data = array();
        $data['send'] = 1;
        return $this->table('send_code_record')->where($condition)->update($data);
    }

    //ip黑名单过滤
    public function ip_black_list($ip){
        //$this->create_black_list(3*24*3600);
        if(empty($ip)){
            return true;
        }
        $condition = array();
        $condition['ip'] = $ip;
        $condition['expire_time'] = array('gt',TIMESTAMP);
        $count = $this->table('sms_blacklist')->where($condition)->count();
        return $count==0;
    }
    public function create_black_list($time){
        $count = $this->table('sms_blacklist')->where(array())->count();
        if($count>0){
            return;
        }
        $sql = 'SELECT ip ,count(*) as count from 33hao_send_code_record group by ip';
        $list= $this->query($sql);
        if($list){
            foreach($list as $val){
                if($val['count']>10){
                    $data = array();
                    $data['ip'] = $val['ip'];
                    $data['add_time'] = TIMESTAMP;
                    $data['expire_time'] = TIMESTAMP +$time;
                    $this->table('sms_blacklist')->insert($data);
                }
            }
        }
    }

    //调用第三方短信接口记录
    public function send_msg($url){
        $data = array();
        $data['url'] = $url;
        $data['add_time'] = TIMESTAMP;
        $this->table('request_send_msg')->insert($data);
    }
}