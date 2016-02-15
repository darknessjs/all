<?php
namespace Home\Model;
use Think\Model;


class UserModel extends Model{
	//自动验证
	protected $_validate = array (
			array (	'name',	'require','用户名不能为空'	) ,
			array (	'pwd',	'require','密码不能为空'	) ,
	);
	
}