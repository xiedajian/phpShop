<?php

/**
 * Created by PhpStorm.
 * User: yuanshian
 * Date: 2016/8/11
 * Time: 18:57
 */
class bankListModel
{
	/**
	 * 广告
	 *
	 * @return array
	 */
	public function getBankListFromDb(){
		$model = Model();
		$ap_list = $model->table('bank_codelist')->limit(10000)->select();
		$result = array();
		foreach ((array)$ap_list as $v) {
			$result[$v['bincode']]=$v;
		}

		return $result;
	}

	/*
	 * 获取银行列表数据
	 * */
	public function getbankList()
	{
		return rkcache('ck_bank_codelist', 'bankListModel::getBankListFromDb');
	}

	/*
	 * 返回银行卡对应的银行信息
	 * */
	public function getbankInfo($cardNo)
	{
		$bankcodelist = $this->getbankList();
		$bank = array();
		if(isset($bankcodelist[substr($cardNo,0,6)])){
			$bank = $bankcodelist[substr($cardNo,0,6)];
		}else if(isset($bankcodelist[substr($cardNo,0,9)])){
			$bank = $bankcodelist[substr($cardNo,0,9)];
		}else if(isset($bankcodelist[substr($cardNo,0,8)])){
			$bank = $bankcodelist[substr($cardNo,0,8)];
		}else if(isset($bankcodelist[substr($cardNo,0,5)])){
			$bank = $bankcodelist[substr($cardNo,0,5)];
		}else if(isset($bankcodelist[substr($cardNo,0,7)])){
			$bank = $bankcodelist[substr($cardNo,0,7)];
		}else if(isset($bankcodelist[substr($cardNo,0,10)])){
			$bank = $bankcodelist[substr($cardNo,0,10)];
		}else if(isset($bankcodelist[substr($cardNo,0,4)])){
			$bank = $bankcodelist[substr($cardNo,0,4)];
		}else if(isset($bankcodelist[substr($cardNo,0,3)])){
			$bank = $bankcodelist[substr($cardNo,0,3)];
		}else if(isset($bankcodelist[substr($cardNo,0,2)])){
			$bank = $bankcodelist[substr($cardNo,0,2)];
		}
		else{
			$bank['id']='';
			$bank['bincode']='';
			$bank['name']='未知的银行卡';
			$bank['orgcode']='';
			$bank['stylecode']='0';
			$bank['fullname']='未知';
			$bank['cardname']='未知';
			$bank['cardtype']='未知';
		}
		return $bank;
	}
}