<?php
namespace Home\Event;

class DBEvent{
/**
	 * 数据库内容导出
	 * @param $host         database host
	 * @param $user         username
	 * @param $pwd          password
	 * @param $db           database name
	 * @param $table        only dump one table
	 * @param $filename     custom file to write output content
	 */
	public static function dbDump($table = null, $filename = null,$type=0) {
		$name = $filename;
		$host=C('DB_HOST');
		$user=C('DB_USER');
		$pwd=C('DB_PWD');
		$db=C('DB_NAME');
		$mysqlconlink = mysql_connect($host, $user, $pwd, true);
		if (!$mysqlconlink){
			echo json_encode(array('result'=>'数据库连接失败','code'=>0));
			exit();
		}
		mysql_set_charset( 'utf8', $mysqlconlink );
		$mysqldblink = mysql_select_db($db,$mysqlconlink);
		if (!$mysqlconlink){
			echo json_encode(array('result'=>'数据库连接失败','code'=>0));
			exit();
		}
		$tabelstobackup = array();
		$result = mysql_query("SHOW TABLES FROM `$db`");
		if (!$result){
			echo json_encode(array('result'=>sprintf('Database error %1$s for query %2$s', mysql_error(), "SHOW TABLE STATUS FROM `$db`;"),'code'=>0));
			exit();
		}
		while ($data = mysql_fetch_row($result)) {
			if(empty($table)) {
				$tabelstobackup[] = $data[0];
			} else{
				foreach ($table as $t){
					if(strtolower($data[0]) == strtolower($t)){  //only dump one table
						$tabelstobackup[] = $data[0];
					}
				}
			}
		}
		if (count($tabelstobackup)>0) {
			$result=mysql_query("SHOW TABLE STATUS FROM `$db`");
			if (!$result)
				echo sprintf('Database error %1$s for query %2$s', mysql_error(), "SHOW TABLE STATUS FROM `$db`;")."<br/>";
			while ($data = mysql_fetch_assoc($result)) {
				$status[$data['Name']]=$data;
			}
			if(!is_dir("./dataBackup")){
				mkdir("./dataBackup");
			}
			if(!isset($filename)) {
				$date = date('YmdHis');
				$filename = "./dataBackup/{$db}.{$date}.sql";
			}else{
				$filename = "./dataBackup/{$filename}.sql";
			}
			if ($file = fopen($filename, 'wb')) {
				
				if(empty($table)) {    //if not only dump single table, dump database create sql
					self::_db_dump_create_database($db, $file);
				}
				fwrite($file, "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n");
				fwrite($file, "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n");
				fwrite($file, "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n");
				fwrite($file, "/*!40101 SET NAMES '".mysql_client_encoding()."' */;\n");
				fwrite($file, "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;\n");
				fwrite($file, "/*!40103 SET TIME_ZONE='".mysql_result(mysql_query("SELECT @@time_zone"),0)."' */;\n");
				fwrite($file, "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n");
				fwrite($file, "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n");
				fwrite($file, "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n");
				fwrite($file, "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n\n");
				foreach($tabelstobackup as $table) {
					//echo sprintf('Dump database table "%s"',$table)."<br/>";
					self::need_free_memory(($status[$table]['Data_length']+$status[$table]['Index_length'])*3);
					self::_db_dump_table($table,$status[$table],$file);
				}
				fwrite($file, "\n");
				fwrite($file, "/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;\n");
				fwrite($file, "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n");
				fwrite($file, "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;\n");
				fwrite($file, "/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;\n");
				fwrite($file, "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n");
				fwrite($file, "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n");
				fwrite($file, "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n");
				fwrite($file, "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;\n");
				fclose($file);
				$sql = "insert into spider_data_backup(name,type,path) values('$name','$type','$filename')";
				mysql_query($sql);
				echo json_encode(array('result'=>'备份数据成功','code'=>1));
				mysql_close ( $mysqlconlink);
			} else {
				echo json_encode(array('result'=>'备份数据失败','code'=>0));
			}
		} else {
			echo json_encode(array('result'=>'没有数据表备份','code'=>0));
			//echo 'No tables to dump'."<br/>";
		}
	}

	protected static function _db_dump_create_database($dbname, $file) {
		$sql = "SHOW CREATE DATABASE `".$dbname."`";
		$result=mysql_query($sql);
		if (!$result) {
			echo json_encode(array('result'=>sprintf('Database error %1$s for query %2$s', mysql_error(), $sql),'code'=>0));
			//echo sprintf('Database error %1$s for query %2$s', mysql_error(), $sql)."<br/>";
			return false;
		}
		$dbstruc=mysql_fetch_assoc($result);
		fwrite($file, str_ireplace('CREATE DATABASE', 'CREATE DATABASE IF NOT EXISTS', $dbstruc['Create Database']).";\n");
		fwrite($file, "USE `{$dbname}`;\n");
	}

	protected static function _db_dump_table($table,$status,$file) {
		fwrite($file, "\n");
		fwrite($file, "DROP TABLE IF EXISTS `" . $table .  "`;\n");
		fwrite($file, "/*!40101 SET @saved_cs_client     = @@character_set_client */;\n");
		fwrite($file, "/*!40101 SET character_set_client = '".mysql_client_encoding()."' */;\n");
		$result=mysql_query("SHOW CREATE TABLE `".$table."`");
		if (!$result) {
			echo json_encode(array('result'=>sprintf('Database error %1$s for query %2$s', mysql_error(), "SHOW CREATE TABLE `".$table."`"),'code'=>0));
			//echo sprintf('Database error %1$s for query %2$s', mysql_error(), "SHOW CREATE TABLE `".$table."`")."<br/>";
			return false;
		}
		$tablestruc=mysql_fetch_assoc($result);
		fwrite($file, $tablestruc['Create Table'].";\n");
		fwrite($file, "/*!40101 SET character_set_client = @saved_cs_client */;\n");
		$result=mysql_query("SELECT * FROM `".$table."`");
		if (!$result) {
			echo sprintf('Database error %1$s for query %2$s', mysql_error(), "SELECT * FROM `".$table."`")."<br/>";
			return false;
		}
		if ($status['Engine']=='MyISAM')
			fwrite($file, "/*!40000 ALTER TABLE `".$table."` DISABLE KEYS */;\n");
		while ($data = mysql_fetch_assoc($result)) {
			$keys = array();
			$values = array();
			foreach($data as $key => $value) {
				if($value === NULL)
					$value = "NULL";
				elseif($value === "" or $value === false)
				$value = "''";
				elseif(!is_numeric($value))
				$value = "'".mysql_real_escape_string($value)."'";
				$values[] = $value;
			}
			fwrite($file, "INSERT INTO `".$table."` VALUES ( ".implode(", ",$values)." );\n");
		}
		if ($status['Engine']=='MyISAM')
			fwrite($file, "/*!40000 ALTER TABLE ".$table." ENABLE KEYS */;\n");
	}
	protected static function need_free_memory($memneed) {
		if (!function_exists('memory_get_usage'))
			return;
		$needmemory=@memory_get_usage(true) + self::inbytes($memneed);
		if ($needmemory > self::inbytes(ini_get('memory_limit'))) {
			$newmemory=round($needmemory/1024/1024)+1 .'M';
			if ($needmemory>=1073741824)
				$newmemory=round($needmemory/1024/1024/1024) .'G';
			if ($oldmem=@ini_set('memory_limit', $newmemory)){
				echo json_encode(array('result'=>sprintf('Memory increased from %1$s to %2$s','backwpup',$oldmem,@ini_get('memory_limit')),'code'=>0));
				//echo sprintf('Memory increased from %1$s to %2$s','backwpup',$oldmem,@ini_get('memory_limit'))."<br/>";
			}
			else{
				echo json_encode(array('result'=>sprintf('Can not increase memory limit is %1$s','backwpup',@ini_get('memory_limit')),'code'=>0));
				//echo sprintf('Can not increase memory limit is %1$s','backwpup',@ini_get('memory_limit'))."<br/>";
			}
		}
	}

	protected static function inbytes($value) {
		$multi = strtoupper(substr(trim($value), -1));
		$bytes = abs((int)trim($value));
		if ($multi=='G')
			$bytes=$bytes*1024*1024*1024;
		if ($multi=='M')
			$bytes=$bytes*1024*1024;
		if ($multi=='K')
			$bytes=$bytes*1024;
		return $bytes;
	}
	
	// 锁定数据库，以免备份或导入时出错
	private function lock($tablename, $op = "WRITE") {
		if (mysql_query ( "lock tables " . $tablename . " " . $op ))
			return true;
		else
			return false;
	}
	
	// 解锁
	private function unlock() {
		if (mysql_query ( "unlock tables" ))
			return true;
		else
			return false;
	}
	
	public static function  restore($sqlfile)
	{
		// 检测文件是否存在
		if (! file_exists ( $sqlfile ))
		{
			echo json_encode(array('result'=>'文件不存在！请检查','code'=>0));
			exit ;
		}
		$host=C('DB_HOST');
		$user=C('DB_USER');
		$pwd=C('DB_PWD');
		$db=C('DB_NAME');
		$mysqlconlink = mysql_connect($host, $user, $pwd, true);
		if (!$mysqlconlink){
			echo json_encode(array('result'=>'数据库连接失败','code'=>0));
			exit();
		}
		mysql_set_charset( 'utf8', $mysqlconlink );
		$mysqldblink = mysql_select_db($db,$mysqlconlink);
		if (!$mysqlconlink){
			echo json_encode(array('result'=>'数据库连接失败','code'=>0));
			exit();
		}
		self::lock ( $mysqldblink );
		if (self::import ( $sqlfile )) {
			echo json_encode(array('result'=>'数据库导入成功！','code'=>1));
		}
		self::unlock();
		mysql_close ( $mysqlconlink);
	}
	
	/**
	 * 将sql导入到数据库（普通导入）
	 *
	 * @param string $sqlfile
	 * @return boolean
	 */
	private function import($sqlfile) {
		// sql文件包含的sql语句数组
		$sqls = array ();
		$f = fopen ( $sqlfile, "rb" );
		// 创建表缓冲变量
		$create = '';
		while ( ! feof ( $f ) ) {
			// 读取每一行sql
			$line = fgets ( $f );
			// 如果包含'-- '等注释，或为空白行，则跳过
			
			if (trim ( $line ) == '' ||preg_match ( '/\/\*!.+\*\/?/', $line, $match )) {
				continue;
			}
			// 如果结尾包含';'(即为一个完整的sql语句，这里是插入语句)，并且不包含'ENGINE='(即创建表的最后一句)，
			if (! preg_match ( '/;/', $line, $match ) || preg_match ( '/ENGINE=/', $line, $match )) {
				// 将本次sql语句与创建表sql连接存起来
				$create .= $line;
				// 如果包含了创建表的最后一句
				if (preg_match ( '/ENGINE=/', $create, $match )) {
					// 则将其合并到sql数组
					$sqls [] = $create;
					// 清空当前，准备下一个表的创建
					$create = '';
				}
				// 跳过本次
				continue;
			}
			$sqls [] = $line;
		}
		fclose ( $f );
		// 循环sql语句数组，分别执行
		foreach ( $sqls as $sql ) {
			str_replace ( "\n", "", $sql );
			if (! mysql_query ( trim ( $sql ) )) { 
				echo json_encode(array('result'=>mysql_error (),'code'=>0));
				return false;
			}
		}
		return true;
	}
	
	//建表和配置删除规则
	public static function createTableAndRule() {
		$host = C ( 'DB_HOST' );
		$user = C ( 'DB_USER' );
		$pwd = C ( 'DB_PWD' );
		$db = C ( 'DB_NAME' );
		$conn = mysql_connect ($host, $user, $pwd ) or  die ( json_encode(array('result'=>'数据库不能连接.','code'=>0)) );
		mysql_set_charset( 'utf8', $conn );
		if(!mysql_select_db ( $db, $conn )){
			$sqlstr = "create database ".$db;
			mysql_query($sqlstr) or die ( json_encode(array('result'=>'不能创建数据库，请检查权限.','code'=>0)) );
			mysql_select_db ( $db, $conn ) or die ( json_encode(array('result'=>'不能连接数据库.'.$db,'code'=>0)) );
			//报警规则表
			$sql = "CREATE TABLE `spider_alert_rule` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `alarm_way` int(11) NOT NULL DEFAULT '1' COMMENT '报警方式，0全选,1声音，2邮件',
				  `task_id` varchar(500) DEFAULT NULL COMMENT '\"\"-适用于所有,\"1,2\"-适用于ID为1/2的任务',
				  `rule_alert` varchar(1000) DEFAULT NULL COMMENT '预警关键字, 正则表达式,是否升级到规则引擎?',
				  `exact` int(11) NOT NULL DEFAULT '0' COMMENT '是否精确匹配，0-模糊，1-精确',
				  `enabled` int(11) NOT NULL DEFAULT '1' COMMENT '是否启用，0-不启用，1-启用',
				  `deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0-未删除，1-已删除',
				  `alarm_name` varchar(100) NOT NULL COMMENT '预警规则名称',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			//采集内容表
			$sql = "CREATE TABLE `spider_content_collect` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `time` int(11) NOT NULL DEFAULT '0' COMMENT '采集时间',
				  `task_type` int(11) NOT NULL DEFAULT '1' COMMENT '所属任务类型,0-无效1-问答,2-新闻',
				  `task_id` int(11) NOT NULL DEFAULT '1',
				  `time_publish` int(11) DEFAULT '0' COMMENT '新闻发布时间',
				  `title_content` varchar(1000) DEFAULT NULL COMMENT '标题, ',
				  `text_content` text COMMENT '采集到的信息文本, ',
				  `url_source` varchar(500) NOT NULL COMMENT '信息来源地址',
				  `feature_content` char(100) DEFAULT NULL COMMENT '信息特征值, 用于去重',
				  `alert` int(11) NOT NULL DEFAULT '0' COMMENT '0-不符合报警条件,1-已报警',
				  `flag` int(11) NOT NULL DEFAULT '0' COMMENT '0-新生成,1-AlertServer处理过，是一个处理流程',
				  `deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0-未删除,1-已删除',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1062 DEFAULT CHARSET=utf8;";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			//用户表
			$sql = "CREATE TABLE `spider_user` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `name` char(120) NOT NULL COMMENT '用户名',
				  `pwd` varchar(300) NOT NULL COMMENT '密码',
				  `type` int(11) NOT NULL DEFAULT '1' COMMENT '0-admin,1-user',
				  `email` varchar(1000) DEFAULT NULL COMMENT '支持多个, \",\" 分隔',
				  `alert_type` varchar(100) DEFAULT '0' COMMENT '\"0\"-不报警,1-页面通知,2-邮件通知,\"1,2\"-页面,邮件',
				  `deleted` int(1) NOT NULL DEFAULT '0' COMMENT '0-未删除,1-已删除',
				  `ip` varchar(100) DEFAULT NULL COMMENT '上次登录ip地址',
				  `lock` int(11) NOT NULL DEFAULT '1' COMMENT '0，锁了，1没有锁',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			$sql = " INSERT INTO `spider_user` VALUES ('1', 'admin', 'admin', '1', '', '0', '0', '', '1');";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			//任务表
			$sql = "CREATE TABLE `spider_task` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `page_count` int(11) DEFAULT '1' COMMENT '每次采集的页面条数',
				  `task_name` char(200) NOT NULL COMMENT '任务名, 不重复',
				  `task_type` int(11) NOT NULL DEFAULT '0' COMMENT '0-无效,1-问答,2-新闻',
				  `url_start` varchar(500) DEFAULT NULL COMMENT '爬虫起始地址，只有问答需要',
				  `rule_match` text COMMENT '爬虫规则',
				  `enabled` int(11) DEFAULT '1' COMMENT '0.不启动，1启动',
				  `count_thread_max` int(11) NOT NULL DEFAULT '1' COMMENT '可使用的线程数量',
				  `time_interval` int(11) NOT NULL DEFAULT '0' COMMENT '爬虫间隔时间, 单位秒，-1：已经执行过了，0：未执行，单次，>0重复的间隔时间',
				  `task_status` int(11) NOT NULL DEFAULT '2' COMMENT '0-无效,1-运行中,2-停止',
				  `schedule_type` int(11) DEFAULT '1' COMMENT '1-立即，2-多次',
				  `pid` int(11) DEFAULT NULL COMMENT '进程ID',
				  `time_start` int(11) DEFAULT NULL COMMENT '爬虫本次执行启动时间',
				  `time_last` int(11) DEFAULT NULL COMMENT '最后更新时间',
				  `count_thread` int(11) DEFAULT NULL COMMENT '实际运行的线程数量',
				  `count_success` int(11) DEFAULT '0' COMMENT '本次启动成功采集内容数量',
				  `count_failed` int(10) unsigned DEFAULT '0' COMMENT '采集失败数量',
				  `delete` int(11) NOT NULL DEFAULT '0' COMMENT '0-未删除,1-已删除',
				  `search_key` varchar(100) DEFAULT '' COMMENT '新闻搜索关键词',
				  `search_id` varchar(100) DEFAULT NULL COMMENT '收索引擎id,隔开，只有新闻需要',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			//搜索引擎表
			$sql = "CREATE TABLE `spider_search_engine` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `se_name` varchar(100) NOT NULL DEFAULT '搜索引擎名字',
				  `se_rule` text COMMENT '爬虫规则，只有新闻要',
				  `se_add` varchar(500) NOT NULL DEFAULT '搜索引擎地址',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			
			//任务启动时间表
			$sql = "CREATE TABLE `spider_schedule` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `task_id` int(11) NOT NULL,
				  `time` int(11) NOT NULL COMMENT '任务启动时间',
				  `flag` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			
			//参数表
			$sql = "CREATE TABLE `spider_dict` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `dict_name` char(10) NOT NULL COMMENT '名字',
				  `dict_value` varchar(255) DEFAULT NULL COMMENT '参数',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			$sql = " INSERT INTO `spider_dict` VALUES ('1', 'delete_day', '30');";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			
			//用户已读表
			$sql = "CREATE TABLE `spider_user_read` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `uid` int(10) unsigned NOT NULL,
				  `ccid` int(10) unsigned NOT NULL COMMENT '采集内容id',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			
			//建立唯一索引
			$sql = " create unique index feature on spider_content_collect(feature_content)";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			
			//备份数据表
			$sql="CREATE TABLE `spider_data_backup` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '备份文件名字',
				  `name` varchar(120) NOT NULL,
				  `type` int(11) NOT NULL DEFAULT '0' COMMENT '：系统配置，1:采集数据，2搜索引擎',
				  `path` varchar(300) NOT NULL COMMENT '备份文件路径',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			
			//配置删除规则  starit*************
			mysql_query("SET GLOBAL event_scheduler = ON;");
			
			//test
			/*$sql = "CREATE TABLE `testaa` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) DEFAULT NULL,
				  `time` int(10) unsigned DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );*/
			
			//创建存储过程
			$sql="CREATE PROCEDURE delete_collect(my_day int)
				  BEGIN
				    	DECLARE my_time INT;
						SET my_time=UNIX_TIMESTAMP(CURRENT_TIMESTAMP  - INTERVAL my_day DAY);
					
						DELETE spider_user_read from spider_user_read JOIN spider_content_collect ON spider_user_read.ccid = spider_content_collect.id where spider_content_collect.time <= my_time;
						DELETE from spider_content_collect where time <= my_time;
				  END";
			mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
			
			$rule = "CREATE EVENT delete_content_collect
					 ON SCHEDULE EVERY 1 DAY
					 DO  CALL delete_collect(30);";
			mysql_query($rule); 			
			
			//end***********
			
		}
		mysql_close ( $conn);
	}
	
	//修改数据库删除规则
	public static function updateDataRule($day){
		$host = C ( 'DB_HOST' );
		$user = C ( 'DB_USER' );
		$pwd = C ( 'DB_PWD' );
		$db = C ( 'DB_NAME' );
		$conn = mysql_connect ($host, $user, $pwd ) or  die ( json_encode(array('result'=>'数据库不能连接.','code'=>0)) );
		mysql_set_charset( 'utf8', $conn );
		if(mysql_select_db ( $db, $conn )){
			$rule = "ALTER EVENT delete_content_collect
				ON SCHEDULE EVERY 1 DAY
				DO  CALL delete_collect($day);";
			mysql_query ( $rule ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
		}
		$sql = "UPDATE `spider_dict` SET dict_value = '".$day."' where dict_name = 'delete_day'";
		mysql_query ( $sql ) or die ( json_encode(array('result'=>mysql_error (),'code'=>0)) );
		echo json_encode(array('result'=>'修改成功','code'=>1));
		mysql_close ( $conn);
		
	}
}

