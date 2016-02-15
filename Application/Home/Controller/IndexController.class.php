<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Home\Event\DBEvent;
		class IndexController extends Controller {

	public function index(){
		$this->assign('title','主页');
		$this->assign('index','');
		$this->display('');
	}


	public function form(){
		$this->assign('title','主页');
		$this->display('');
	}



}