<?php

class dashboard_it
{

	public static $path_image = 'http://soloabadi-server.ddns.net/system-sobad-group/asset/img/user';

	public static function get_profile_user($user = 0)
	{
		$default = array(
			'_nickname' => '-',
			'notes_pict' => 'no-profile.jpg',
		);

		$users = request_curl::get_user($user);
		$data = isset($users[0]) ? $users[0] : $default;

		$data['url'] = self::$path_image;
		$data['name'] = $data['_nickname'];
		$data['picture'] = $data['notes_pict'];

		ob_start();
		theme_layout('profile_user', $data);
		return ob_get_clean();
	}

	public static function _image_profile($data = [])
	{
		$data['url'] = self::$path_image;
		$data['name'] = $data['_nickname'];
		$data['picture'] = empty($data['notes_pict']) ? 'no-profile.jpg' : $data['notes_pict'];
		$data['jobtitle'] = $data['meta_value_divi'];

		ob_start();
		theme_layout('image_profile', $data);
		return ob_get_clean();
	}

	public static function get_status_project($user = 0)
	{
		$status = array(
			'actual' => 0,
			'schedule' => 0,
			'achievment' => 0
		);

		$data = request_curl::get_handle_projects($user);
		foreach ($data as $key => $val) {
			if ($val['work_status'] == 4 || $val['work_status'] == 6) {
				$status['actual'] += 1;
			}
		}

		$status['schedule'] = count($data);
		$status['achievment'] = $status['actual'] > 0 ? round($status['actual'] / $status['schedule'] * 100, 1) : 0;

		return $status;
	}

	public static function _conv_work_type($type = 0)
	{
		$args = array(1 => 'UI/UX', 'System Analyst', 'Programmer', 'Tester');
		return isset($args[$type]) ? $args[$type] : '-';
	}

	public static function _conv_work_day($type = 0)
	{
		$args = array(1 => 'day', 'week', 'month', 'year');
		return isset($args[$type]) ? $args[$type] : '-';
	}

	public static function _conv_color_gantt($type = 0)
	{
		$args = array(
			0	=> '#F4F4F4',
			3	=> '#F62121',
			4	=> 'linear-gradient(180deg, #D9D9D9 0%, #D1D1D1 0.01%, #B7B7B7 100%)',
			8 	=> 'linear-gradient(180deg, #A430E1 0%, #C150F4 100%)',
			52	=> 'linear-gradient(180deg, #71D0C8 0%, #86EEE5 100%)',
			79	=> 'linear-gradient(126.69deg, #EFDCFF 28.65%, #E2BAFA 84.56%)',
			171	=> 'linear-gradient(180deg, #FBD289 0%, #FACF7D 100%)',
			177	=> 'linear-gradient(130.49deg, #15BA6B 26.97%, #23DA82 97.85%)',
			185	=> 'linear-gradient(116.09deg, #009EF7 24.2%, #3FB8FC 49.93%, #6FCBFF 86.73%)'
		);

		return isset($args[$type]) ? $args[$type] : '#eee';
	}

	public static function format_date($date=''){
		if(empty($date) || $date == '0000-00-00' || $date == '1970-01-01'){
			return '-';
		}

		$date = strtotime($date);
		$date = date('d M Y',$date);

		return $date;
	}

	public static function format_date_month($date=''){
		if(empty($date) || $date == '0000-00-00' || $date == '1970-01-01'){
			return '';
		}

		$date = strtotime($date);
		$date = date('d M',$date);

		return $date;
	}

	// ----------------------------------------------------------
	// ----- Load Here ------------------------------------------
	// ----------------------------------------------------------

	public static function index()
	{
		$project = self::_project_team();
		$progress = self::_progress_team();
		$complain = self::_complain_bug();
		$gantt_chart = self::_gantt_project();

		$head = array(
			array(
				'col' => 4,
				'func' => 'project_team',
				'data' => $project
			),
			array(
				'col' => 4,
				'func' => 'progress_team',
				'data' => $progress
			),
			array(
				'col' => 4,
				'func' => 'complain_bug',
				'data' => $complain
			)
		);

		$users = request_curl::get_teams();
		foreach ($users as $key => $val) {
			$user = self::_project_user($val);

			$body[] = array(
				'col' => 4,
				'func' => 'project_user',
				'data' => $user
			);
		}

		$gantt = array(
			array(
				'height'=> false,
				'col' 	=> 12,
				'func' 	=> 'project_gantt_chart',
				'data' 	=> $gantt_chart
			)
		);

		$data = ['head' => $head, 'body' => $body, 'gantt' => $gantt];

		return $data;
	}

	protected static function _project_team()
	{
		$data = array();
		$teams = request_curl::get_teams();

		$now = date('Y-m-d');
		$active = 0;
		foreach ($teams as $key => $val) {
			$status = request_curl::check_presensi($val['ID']);
			$status = $status == 1 ? 1 : 0;

			$data['team'][] = array(
				'url' 		=> self::$path_image,
				'name' 		=> $val['_nickname'],
				'picture' 	=> $val['notes_pict'],
				'active'	=> $status
			);

			$active += $status;
		}

		$data['active'] = $active;
		$data['total'] = count($teams);
		$data['date'] = format_date_id($now);
		$data['title'] = '<span>Daily Task<br><label>IT Dept</label></span>';

		return $data;
	}

	protected static function _progress_team()
	{
		$un_job = $job = $table = array();

		$pre = $act = $hold = $rev = $un_sch = $comp = $total = 0;
		$project = request_curl::get_team_projects();
		foreach ($project as $key => $val) {
			if ($val['status'] == 0) {
				$pre += 1;
			} else if ($val['status'] == 7) {
				$rev += 1;
			} else if ($val['status'] == 3) {
				$hold += 1;
			} else if ($val['status'] == -1) {
				$un_sch += 1;
			} else if ($val['status'] == 6) {
				$comp += 1;
			} else {
				$act += 1;
			}
		}

		$total = $pre + $rev + $hold + $act;

		$job['title'] = 'Department Job';
		$job['total'] = $total;
		$job['label'] = array(
			array(
				'label' => 'Prepare',
				'color' => '#D9D9D9',
				'qty' => $pre
			),
			array(
				'label' => 'Active',
				'color' => '#15BA6B',
				'qty' => $act
			),
			array(
				'label' => 'Hold',
				'color' => '#F62121',
				'qty' => $hold
			),
			array(
				'label' => 'Revisi',
				'color' => '#FAB05C',
				'qty' => $rev
			)
		);

		// Unschedule Job
		$thead = array(
			'No.' => array('15%', 'center'),
			'Job Title' => array('auto', 'left')
		);

		$table['thead'] = array();
		$table['tbody'] = array();

		foreach ($thead as $key => $val) {
			$table['thead'][] = array(
				'width' => $val[0],
				'align' => $val[1],
				'label' => $key
			);
		}

		$complain = request_curl::get_complains();
		$total = count($complain);

		$no = 1;
		foreach ($complain as $key => $val) {
			$feature = '<strong>' . $val['meta_value_feat'] . '</strong> - ' . $val['meta_value_role'];

			$table['tbody'][] = array(
				'no' => array(
					'center',
					$no++ . '.'
				),
				'job' => array(
					'left',
					$feature
				),
			);
		}

		$un_job['title'] = 'Unschedule Job';
		$un_job['total'] = $un_sch;
		$un_job['table'] = $table;

		return array(
			'department'	=> $job,
			'unschedule'	=> $un_job
		);
	}

	protected static function _complain_bug()
	{
		$data = $table = array();
		$repair = 0;

		$thead = array(
			'No.' => array('5%', 'center'),
			'Complain' => array('auto', 'left'),
			'Date' => array('17%', 'left'),
			'User' => array('8%', 'left'),
			'Repair' => array('17%', 'left'),
			'PIC' => array('8%', 'left'),
		);

		$table['thead'] = array();
		$table['tbody'] = array();

		foreach ($thead as $key => $val) {
			$table['thead'][] = array(
				'width' => $val[0],
				'align' => $val[1],
				'label' => $key
			);
		}

		$complain = request_curl::get_complains();
		$total = count($complain);

		$no = 1;
		foreach ($complain as $key => $val) {
			$repair += $val['handle_date'] == '0000-00-00' || $val['handle_date'] == '1970-01-01' ? 0 : 1;

			$user = self::get_profile_user($val['user_id']);
			$pic = self::get_profile_user($val['handle_user']);

			$feature = '<strong>' . $val['meta_value_feat'] . '</strong> - ' . $val['meta_value_role'];

			$table['tbody'][] = array(
				'no' => array(
					'center',
					$no++ . '.'
				),
				'compalin' => array(
					'left',
					$feature
				),
				'date' => array(
					'left',
					self::format_date($val['request_date'])
				),
				'user' => array(
					'left',
					$user
				),
				'repair' => array(
					'left',
					self::format_date($val['handle_date'])
				),
				'pic' => array(
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

	public static function _project_user($args = array())
	{
		$default = array(
			'meta_value_feat' => '-',
			'workflow_id' => 0,
			'work_type' => 0,
			'work_time' => 0,
			'work_day' => 0,
			'work_status' => 0,
			'start_date' => '0000-00-00',
			'finish_date' => '0000-00-00',
			'start_actual' => '0000-00-00',
			'finish_actual' => '0000-00-00'
		);

		$work = request_curl::get_handle_projects($args['ID'], "AND work_status='1'");
		$work = isset($work[0]) ? $work[0] : $default;

		$args = array_merge($args, $work);

		$handle = request_curl::get_handle_projects($args['ID'], "AND work_status IN (0,1,2,3,5)");
		$data['total'] = count($handle);

		$data['url'] = self::$path_image;
		$data['name'] = $args['_nickname'];
		$data['picture'] = empty($args['notes_pict']) ? 'no-profile.jpg' : $args['notes_pict'];
		$data['jobtitle'] = $args['meta_value_divi'];

		$data['date_active'] = self::format_date_month($args['finish_date']);
		$data['balance'] = 0;

		$detail = array();
		foreach ($handle as $key => $val) {
			$title = '<strong>' . $val['meta_value_feat'] . '</strong> - ' . $val['meta_value_role'];
			$plan = self::format_date_month($val['start_date']) .'-' . self::format_date_month($val['finish_date']);

			$actual = self::format_date_month($val['start_actual']) .'-' . self::format_date_month($val['finish_actual']);

			$progress = isset($val['work_progress']) ? $val['work_progress'] : 0;
			$balance = 0;
			$worktime = $val['work_time'];

			$status = '#D9D9D9';
			if($val['work_status'] == 1){
				$status = '#15BA6B';
			}else if($val['work_status'] == 2){
				$status = '#15BA6B';
			}else if($val['work_status'] == 3){
				$status = '#F62121';
			}else if($val['work_status'] == 5){
				$status = '#FAB05C';
			}

			$detail[] = array(
				'title'			=> $title,
				'type'			=> self::_conv_work_type($val['work_type']),
				'progress' 		=> $progress . '%',
				'planning'		=> $plan,
				'actual'		=> $actual,
				'balance'		=> $balance,
				'worktime'		=> $worktime,
				'status'		=> $status
			);
		}

		$data['detail'] = $detail;
		return $data;
	}

	public static function _gantt_project()
	{
		$data = array();

		$d = date('d');
		$m = date('m');
		$y = date('Y');
		$date = date('Y-m-d');

		$week = day_week_range($date);
		$sday = $week['start'];
		$fday = $week['finish'];

		$data['day'] = $d;
		$data['month'] = conv_month_id($m) . ' ' . $y;
		$data['today'] = $date;
		
		$data['week_month'] = $week['week'];
		$data['day_week'] = 7;
		$data['start_week'] = $week['start'];
		$data['finish_week'] = $week['finish'];

		$data['gantt'] = $data['gantt_actual'] = array();

		$teams = request_curl::get_teams();
		foreach ($teams as $key => $val) {
			$member = self::_image_profile($val);

			$data['user'][] = $member;

			$where = "AND ( (start_date BETWEEN '$sday' AND '$fday') OR (start_actual BETWEEN '$sday' AND '$fday') )";
			$detail = request_curl::get_handle_projects($val['ID'],$where);
			foreach ($detail as $ky => $vl) {

				$status = 0;
				if(in_array($vl['work_status'],array(1,2,5))){
					$status = $val['ID'];
				}else if($vl['work_status']==3){
					$status = 3;
				}else if(in_array($vl['work_status'],array(4,6))){
					$status = 4;
				}

				if($vl['start_date'] >= $sday){
					$vl['start_date'] = $vl['start_date'] < $sday ? $sday : $vl['start_date'];
					$vl['finish_date'] = $vl['finish_date'] > $fday ? $fday : $vl['finish_date'];

					$worktime = _conv_date($vl['start_date'],$vl['finish_date']);

					$left = strtotime($vl['start_date']);
					$left = date('w',$left);

					$data['gantt'][] = array(
						'top'	=> $key,
						'left'	=> $left,
						'width'	=> $worktime + 1,
						'label'	=> $vl['meta_value_feat'],
						'note'	=> self::_conv_work_type($vl['work_type']),
						'color'	=> self::_conv_color_gantt($status),
					);
				}

				if($vl['start_actual']=='0000-00-00'){
					continue;
				}

				if($vl['start_actual'] >= $sday || $vl['finish_actual'] <= $fday){
					$vl['start_actual'] = $vl['start_actual'] < $sday ? $sday : $vl['start_actual'];
					$vl['finish_actual'] = $vl['finish_actual'] > $fday ? $fday : $vl['finish_actual'];

					$aleft = strtotime($vl['start_actual']);
					$aleft = date('w',$aleft);

					$fns_act = $vl['finish_actual'] == '0000-00-00' ? date('Y-m-d') : $vl['finish_actual'];
					$worktime = _conv_date($vl['start_actual'],$fns_act);

					$data['gantt_actual'][] = array(
						'top'	=> $key,
						'left'	=> $aleft,
						'width'	=> $worktime + 1,
						'label'	=> $vl['meta_value_feat'],
						'note'	=> self::_conv_work_type($vl['work_type']),
						'color'	=> self::_conv_color_gantt($status),
					);
				}
			}
		}

		return $data;
	}
}