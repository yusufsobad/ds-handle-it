<?php

class request_curl
{
	private static $url = 'http://soloabadi-server.ddns.net/system-sobad-group/include/curl.php';

	private static function send_curl($args = array())
	{
		$url = self::$url;

		$data = sobad_curl::get_data(self::$url, $args);
		$data = json_decode($data, true);

		//	if($data['status']=='error'){
		//		$url = 'http://192.168.1.2:8080/system-absen/include/curl.php';

		//		$data = sobad_curl::get_data($url,$args);
		//		$data = json_decode($data,true);

		if ($data['status'] == 'error') {
			die(_error::_alert_db($data['msg']));
		}
		//	}

		return $data['msg'];
	}

	public static function get_user($user=0)
	{
		$args = array('name','picture','divisi','_nickname');

		$data = array(
			'object'	=> 'abs_user',
			'func'		=> 'get_id',
			'data'		=> array($user,$args)
		);

		return self::send_curl($data);
	}

	public static function get_teams()
	{
		$data = array(
			'object'	=> 'jbd_module',
			'func'		=> '_get_teams_by_divisi',
			'data'		=> array(4)
		);

		return self::send_curl($data);
	}

	public static function get_team_projects()
	{
		$args = array('ID','status');

		$data = array(
			'object'	=> 'sobad_workflow',
			'func'		=> 'get_all',
			'data'		=> array($args)
		);

		return self::send_curl($data);
	}

	public static function get_handle_projects($user=0, $limit = '')
	{
		$args = array('workflow_id','work_type','work_time','work_day','work_status','start_date','finish_date','start_actual','finish_actual');
		$whr = "AND user_id='$user' $limit";

		$data = array(
			'object'	=> 'sobad_workflow_detail',
			'func'		=> 'get_all',
			'data'		=> array($args,$whr)
		);

		return self::send_curl($data);
	}

	public static function get_count_projects($user=0, $limit = '')
	{
		$args = array('ID');
		$whr = "user_id='$user' $limit";

		$data = array(
			'object'	=> 'sobad_workflow_detail',
			'func'		=> 'count',
			'data'		=> array($whr,$args)
		);

		return self::send_curl($data);
	}

	public static function get_complains()
	{
		$args = array('user_id','note_bug','request_date','handle_user','handle_date');
		$whr = "AND status='0' AND type_complain='1'";

		$data = array(
			'object'	=> 'sobad_complain_bug',
			'func'		=> 'get_all',
			'data'		=> array($args,$whr)
		);

		return self::send_curl($data);
	}
}
