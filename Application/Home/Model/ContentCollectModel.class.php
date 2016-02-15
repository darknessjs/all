<?php
namespace Home\Model;


use Think\Model\RelationModel;
class ContentCollectModel extends RelationModel{
	protected  $_link;
		
	public function mySelect($uid){
		$this->_link=array(
				'UserRead'=>array(
						'mapping_type'=>self::HAS_ONE,
						'foreign_key'=>'ccid',
						'mapping_fields'=>'uid',
						'as_fields'=>'uid',
						'condition'=>"uid='$uid'"
				),
		);
	}
	
}