<?php

class dashboard_it{

	public static $path_image = 'http://soloabadi-server.ddns.net/system-sobad-group/asset/img/user';

	public static function get_profile_user($user = 0){
		$default = array(
			'_nickname'		=> '-',
			'notes_pict'	=> 'no-profile.jpg',
		);
		
		$users = request_curl::get_user($user);
		$data = isset($users[0]) ? $users[0] : $default;

		$data['url'] = self::$path_image;
		$data['name'] = $data['_nickname'];
		$data['picture'] = $data['notes_pict'];

		ob_start();
		theme_layout('profile_user',$data);
		return ob_get_clean();
	}

	public static function _image_profile($data = []){
		$data['url'] = self::$path_image;
		$data['name'] = $data['_nickname'];
		$data['picture'] = empty($data['notes_pict']) ? 'no-profile.jpg' : $data['notes_pict'];
		$data['jobtitle'] = $data['meta_value_divi'];

		ob_start();
		theme_layout('image_profile',$data);
		return ob_get_clean();
	}

	public static function get_status_project($user = 0){
		$status = array(
			'actual' 	=> 0,
			'schedule'	=> 0,
			'achievment'=> 0
		);

		$data = request_curl::get_handle_projects($user);
		foreach ($data as $key => $val) {
			if($val['work_status'] == 4){
				$status['actual'] += 1;
			}
		}

		$status['schedule'] = count($data);
		$status['achievment'] = $status['actual'] > 0 ? round($status['actual'] / $status['schedule'] * 100, 1) : 0;

		return $status;
	}

	public static function _conv_work_type($type=0){
		$args = array(1 => 'UI/UX','System Analyst','Programmer','Tester');
		return isset($args[$type]) ? $args[$type] : '-';
	}

	public static function _conv_work_day($type=0){
		$args = array(1 => 'day','week','month','year');
		return isset($args[$type]) ? $args[$type] : '-';
	}

	// ----------------------------------------------------------
	// ----- Load Here ------------------------------------------
	// ----------------------------------------------------------

	public static function index(){
		$project = self::_project_team();
		$progress = self::_progress_team();
		$complain = self::_complain_bug();

		$data = array(
			array(
				'col'	=> 4,
				'func'	=> 'project_team',
				'data'	=> $project
			),
			array(
				'col'	=> 2,
				'func'	=> 'progress_team',
				'data'	=> $progress
			),
			array(
				'col'	=> 6,
				'func'	=> 'complain_bug',
				'data'	=> $complain
			)
		);

		$users = request_curl::get_teams();
		foreach ($users as $key => $val) {
			$user = self::_project_user($val);

			$data[] = array(
				'col'	=> 2,
				'func'	=> 'project_user',
				'data'	=> $user
			);
		}

		return $data;
	}

	protected static function _project_team(){
		$data = array();
		$thead = array(
			'No.'		=> array('8%','center'),
			'Member'	=> array('auto','left'),
			'Actual'	=> array('15%','center'),
			'Schedule'	=> array('18%','center'),
			'Ach. %'	=> array('15%','center'),
		);

		$data['thead'] = array();
		$data['tbody'] = array();

		foreach ($thead as $key => $val) {
			$data['thead'][] = array(
				'width'		=> $val[0],
				'align'		=> $val[1],
				'label'		=> $key
			);
		}

		$no = 1;
		$teams = request_curl::get_teams();
		foreach ($teams as $key => $val) {
			$member = self::_image_profile($val);
			$handle = self::get_status_project($val['ID']);

			$data['tbody'][] = array(
				'no'		=> array(
					'center',
					$no++ . '.'
				),
				'member'	=> array(
					'left',
					$member
				),
				'act'		=> array(
					'center',
					$handle['actual']
				),
				'sch'		=> array(
					'center',
					$handle['schedule']
				),
				'ach'		=> array(
					'center',
					$handle['achievment'] . '%'
				)
			);
		}

		return $data;
	}

	protected static function _progress_team(){
		$data = array();

		$un_sch = $sch = $cmp = $total = 0;
		$data = request_curl::get_team_projects();
		foreach ($data as $key => $val) {
			if($val['status'] == -1){
				$un_sch += 1;
			}else if( $val['status'] == 6){
				$cmp += 1;
			}else{
				$sch += 1;
			}
		}

		$total = count($data);

		$data['title'] = 'Progress<br>Department Job';
		$data['label'] = array(
			array(
				'label'		=> 'Unschedule',
				'color'		=> '#EDECF0',
				'qty'		=> $un_sch
			),
			array(
				'label'		=> 'Scheduled',
				'color'		=> '#FBD289',
				'qty'		=> $sch
			),
			array(
				'label'		=> 'Completed',
				'color'		=> '#3FB8FC',
				'qty'		=> $cmp
			)
		);

		return $data;
	}

	protected static function _complain_bug(){
		$data = $table = array();
		$repair = 0;

		$thead = array(
			'No.'		=> array('5%','center'),
			'Complain'	=> array('auto','left'),
			'Date'		=> array('15%','left'),
			'User'		=> array('12%','left'),
			'Repair'	=> array('15%','left'),
			'PIC'		=> array('12%','left'),
		);

		$table['thead'] = array();
		$table['tbody'] = array();

		foreach ($thead as $key => $val) {
			$table['thead'][] = array(
				'width'		=> $val[0],
				'align'		=> $val[1],
				'label'		=> $key
			);
		}

		$complain = request_curl::get_complains();
		$total = count($complain);

		$no = 1;
		foreach ($complain as $key => $val) {
			$repair += $val['handle_date'] == '0000-00-00' || $val['handle_date'] == '1970-01-01' ? 0 : 1;

			if($no>5){
				continue;
			}

			$user = self::get_profile_user($val['user_id']);
			$pic = self::get_profile_user($val['handle_user']);

			$table['tbody'][] = array(
				'no'		=> array(
					'center',
					$no++ . '.'
				),
				'compalin'	=> array(
					'left',
					$val['note_bug']
				),
				'date'		=> array(
					'left',
					format_date_id($val['request_date'])
				),
				'user'		=> array(
					'left',
					$user
				),
				'repair'		=> array(
					'left',
					format_date_id($val['handle_date'])
				),
				'pic'		=> array(
					'left',
					$pic
				)
			);
		}

		$data['title'] = 'Complain Bug';
		$data['total'] = $total;
		$data['handle'] = $repair;
		$data['table'] = $table;

		return $data;
	}

	public static function _project_user($args = array()){
		$default = array(
			'meta_value_feat'	=> '-',
			'workflow_id' 		=> 0,
			'work_type'			=> 0,
			'work_time'			=> 0,
			'work_day'			=> 0,
			'work_status'		=> 0,
			'start_date'		=> '0000-00-00',
			'finish_date'		=> '0000-00-00',
			'start_actual'		=> '0000-00-00',
			'finish_actual'		=> '0000-00-00'
		);

		$work = request_curl::get_handle_projects($args['ID'],"AND work_status='1'");
		$work = isset($work[0]) ? $work[0] : $default;

		$args = array_merge($args,$work);

		$total = request_curl::get_count_projects($args['ID'],"AND work_status IN (0,1,3)");
		$data['total'] = $total;

		$data['title'] = $args['meta_value_feat'];
		$data['jobdesk'] = self::_conv_work_type($args['work_type']);

		$data['url'] = self::$path_image;
		$data['name'] = $args['_nickname'];
		$data['picture'] = empty($args['notes_pict']) ? 'no-profile.jpg' : $args['notes_pict'];
		$data['jobtitle'] = $args['meta_value_divi'];

		$data['start_date'] = format_date_id($args['start_date']);
		$data['finish_date'] = format_date_id($args['finish_date']);
		$data['start_actual'] = format_date_id($args['start_actual']);
		$data['finish_actual'] = format_date_id($args['finish_actual']);

		$data['progress_width'] = '0%';
		$data['progress'] = '0%';

		$data['worktime'] = $args['work_time'] .' ' . self::_conv_work_day($args['work_day']);

		return $data;
	}
}