<?php
namespace Home\Model;
use Think\Model;


class UserModel extends Model{
	//自动验证
	protected $_validate = array (
			array (	'task_type','require','类型不能为空'	) ,
			array (	'name','require','用户不能为空') ,
			array (	'pwd','require','密码不能为空') ,
			
	);
}