<?php
/**
 * 通知模板表
 *
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');

class pay_agentModel extends Model {


    /**
     * 取单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getPayAgentInfo($condition = array(), $fields = '*') {
        return $this->table('pay_agent')->where($condition)->field($fields)->find();
    }

	/**
	 * 模板列表
	 *
	 * @param array $condition 检索条件
	 * @return array 数组形式的返回结果
	 */
	public function getPayAgentList($condition = array(),$fields = '*'){
	    return $this->table('pay_agent')->where($condition)->field($fields)->select();
	}


	/**
	 * 更改代付人信息
	 * @param array $data
	 * @param array $condition 检索条件
	 * @return bool
	 */
	public function editPayAgent($data = array(), $condition = array())
	{
		return $this->table('pay_agent')->where($condition)->update($data);
	}

	/**
	 * 添加
	 * @param array $data
	 * @return mixed
	 */
	public function addPayAgent($data = array())
	{
		return $this->table('pay_agent')->insert($data);
	}
	/**
	 * 删除
	 */

	public function delPayAgent($condition = array()){
		return $this->table('pay_agent')->where($condition)->delete();
	}
	

}