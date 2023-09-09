<?php

(!defined('THEMEPATH')) ? exit : '';

define('_theme_name', 'dashboard_layout');
define('_theme_folder', basename(__DIR__));

class dashboard_layout extends dashboard_template
{

	public static function load_here($data = [])
    {
    	?>
    		<div class="it-content">
				<?php 
					foreach ($data as $key => $value) {
				?>
					<div id='<?= $key ?>'class="row equal">
					<?php
							foreach ($value as $val) {
                                $style = isset($val['height']) && $val['height']==false ? '' : 'style="height:22vw;';

								$func = $val['func'];
								$args = $val['data'];
	
								echo '<div id class="col-md-'.$val['col'].'" '.$style.'">';
								if (is_callable(array(new self(), $func))) {
									self::{$func}($args);
								}
								echo '</div>';
							}
						?>
					</div>
					<?php } ?>
				</div>
			<?php

            self::_script();
    }

    public static function project_team($data = []){
    	?>
    		<div class="project-team-it box-content">
    			<div class="row">
                    <div class="col-md-6">
                        <div class="title-daily-task"><?= $data['title'] ;?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="calender-daily-task">
                            <div class="icon-daily">
                                <i class="fa fa-calendar">&nbsp;</i>
                            </div>
                            <div class="date-daily">
                                <label id="time-daily"></label>
                                <span id="date-daily"><?= $data['date'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px;">
                        <span>
                            Total Squad:
                            <div class="square-color bg-total-squad"><?= $data['active'] ?></div> /
                            <div class="square-color bg-total-squad" style="background: linear-gradient(180deg, #71D0C8 0%, #86EEE5 100%);color: #fff;"><?= $data['total'] ?></div>
                        </span>
                    </div>
                    <div class="col-md-12">
                        <?php
                            foreach ($data['team'] as $key => $val) {
                                $color = $val['active'] == 1 ? '' : 'background: #BEBEBE;';
                                self::squad_user($val,$color);
                            }
                        ?>
                    </div>
                </div>
    		</div>
    	<?php
    }

    public static function progress_team($data = []){
        $job = $data['department'];
        $unsc = $data['unschedule'];

    	?>
    		<div class="progress-team-it box-content">
    			<div class="row" style="height: 100%;">
                    <div class="col-md-6" style="border-right: 1px solid #c4c4c4;height: 100%;">
                        <div class="box-row-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="title-progress"><?= $job['title'] ;?></label>
                                </div>
                                <div class="col-md-12">
                                    <div class="job-active">
                                        <table class="table-active-job" style="width: 100%;">
                                            <tr>
                                                <td style="width:50%;">
                                                    <span>Job Active:</span>
                                                </td>
                                                <td style="width:50%;font-size: 45px;text-align: center;">
                                                    <label><?= $job['total'] ;?></label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="label-progress">
                                        <?php
                                            foreach ($job['label'] as $key => $val) {
                                                self::label_progress($val);
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="height: 100%;">
                        <div class="header-unschedule">
                            <label class="title-progress unschedule"><?= $unsc['title'] ;?></label>
                            <div class="square-color" style="color: #555555;background-color: #D9D9D9;font-size: 17px;font-weight: 600;"><?= $unsc['total'] ;?></div>
                        </div>
                        <div class="unschedule-job">
                            <?php self::table_content($unsc['table']) ?>
                        </div>
                    </div>
    			</div>
    		</div>
    	<?php
    }

    public static function complain_bug($data = []){
    	?>
    		<div class="complain-bug-it box-content">
    			<div class="header-complain">
    				<label class="title-progress"><?= $data['title'] ;?></label>
    				<span>Total Bug: 
    					<div class="square-color" style="color: #FAB05C;background-color: #FFEFDD;"><?= $data['total'] ;?></div>
    				</span>
    				<span>Repair Bug: 
    					<div class="square-color" style="color: #15BA6B;background-color: #E1FFF1;"><?= $data['handle'] ;?></div>
    				</span>
    			</div>
                <div class="table-complain">
                    <?php self::table_content($data['table']) ?>
                </div>
    		</div>
    	<?php
    }

    public static function project_user($data = []){
    	?>
    		<div class="project-user-it box-content">
    			<div class="project-total">
    				<label><?= $data['total'] ;?></label>
    			</div>

                <div class="profile-user-project">
                    <?php self::image_profile($data) ;?>
                </div>
    			<div class="last-project">
                    <span>Last Job &nbsp;&nbsp;: <?= $data['date_active'] ;?></span>
                    <span>Kum BLC : <?= $data['balance'] ;?></span>
                </div>
                <div class="detail-project">
                    <?php foreach ($data['detail'] as $key => $val) :?>
                        <div class="box-content-project">
                            <div class="box-project">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="detail-title-project">
                                            <label><?= $val['title'] ;?></label>
                                        </div>
                                        <div class="detail-type-project">
                                            <span><?= $val['type'] ;?></span>
                                        </div>
                                        <div class="progress-project">
                                            <div class="progress-block">
                                                <div class="progress-bar" style="width: <?= $val['progress'] ;?>"></div>
                                            </div>
                                            <label><?= $val['progress'] ;?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-7" style="padding: 0px !important;">
                                                <div class="box-date-project">
                                                    <div class="bg-box-date planning">
                                                        <span>&nbsp;</span>
                                                    </div>
                                                    <div class="title-box-date">
                                                        <span><?= $val['planning'] ;?></span>
                                                    </div>
                                                </div>
                                                <div class="box-date-project">
                                                    <div class="bg-box-date actual">
                                                        <span>&nbsp;</span>
                                                    </div>
                                                    <div class="title-box-date">
                                                        <span><?= $val['actual'] ;?></span>
                                                    </div>
                                                </div>
                                                <div class="box-date-project">
                                                    <div class="bg-box-date balance">
                                                        <span>&nbsp;</span>
                                                    </div>
                                                    <div class="title-box-date">
                                                        <span><?= $val['balance'] ;?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="square-color" style="width: 100%;height: 100%;padding: 8px;background: #F3DDFF;color: #9D28DD;">
                                                    <label><?= $val['worktime'];?></label>
                                                    <span>day</span>
                                                </div>
                                            </div>
                                            <div class="col-md-1" style="padding: 0px !important;">
                                                <div class="circle-color" style="background:<?= $val['status'] ;?>;margin-top: 24px;">&nbsp;</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
    		</div>
    	<?php
    }

    public static function project_gantt_chart($data = []){
        $sdate = strtotime($data['start_week']);
        $wdt = 100 / $data['day_week'];

        $today = strtotime($data['today']);
        $tday = date('w',$today);

        $today = ($tday * $wdt);
        ?>
            <div class="gantt-project-it box-content">
                <div class="gantt-content-table">
                    <div class="gantt-header">
                        <div class="gantt-row">
                            <div class="gantt-col member-area">
                                <label>Member</label>
                            </div>
                            <div class="gantt-col date-area">
                                <div class="date-month-text">
                                    <div class="bag-month">&nbsp;</div>
                                    <label><?= $data['month'] ;?></label>
                                    <div class="button-directional">
                                        <a class="angle-left" href="javascript:void(0)"><i class="fa fa-angle-left">&nbsp;</i></a>
                                        <a class="angle-right" href="javascript:void(0)"><i class="fa fa-angle-right">&nbsp;</i></a>
                                    </div>
                                </div>
                                <div class="date-week-year">
                                    <div class="bag-month">&nbsp;</div>
                                    <label>Week <?= $data['week_month'] ;?></label>
                                    <div class="button-directional">
                                        <a class="angle-left" href="javascript:void(0)"><i class="fa fa-angle-left">&nbsp;</i></a>
                                        <a class="angle-right" href="javascript:void(0)"><i class="fa fa-angle-right">&nbsp;</i></a>
                                    </div>
                                </div>
                                <div class="date-month-day">
                                    <div class="bag-month">&nbsp;</div>
                                    <div class="bag-today" style="width:<?= $wdt;?>%;left: <?= $today ;?>%;">&nbsp;</div>
                                    <?php
                                        for ($i=0; $i < $data['day_week']; $i++) { 
                                            $dt = $i == 0 ? $sdate : strtotime("+$i days",$sdate);
                                            $dt = date('d',$dt);
                                            
                                            echo '<label class="day-month-text" style="width:'.$wdt.'%">' . $dt . '</label>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gantt-body">
                        <div class="gantt-row">
                            <div class="gantt-col member-area">
                                <?php
                                    foreach ($data['user'] as $key => $val) {
                                        $no = $key + 1;
                                        echo '
                                            <div class="team-row">
                                                <div class="no-user">' . $no . '.</div>
                                                ' . $val . '
                                            </div>
                                        ';
                                    }
                                ?>
                            </div>
                            <div class="gantt-col date-area">
                                <div class="gantt-position">
                                    <?php
                                        foreach ($data['user'] as $key => $val) {
                                            echo '<div class="gantt-task">';
                                            for ($i=0; $i < $data['day_week']; $i++) {
                                                $bg = $i == $tday ? 'background: #DCF2FF;' : '';
                                                echo '<div class="gantt-grid" style="width:'.$wdt.'%;'.$bg.'">&nbsp;</div>';
                                            }
                                            echo '</div>';
                                        }
                                    ?>
                                </div>
                                <?php
                                    foreach ($data['gantt'] as $key => $val) {
                                        $top = empty($val['top']) ? 0 : $val['top'] * 50;
                                        $left = empty($val['left']) ? 0 : $val['left'] * $wdt;

                                        $width = empty($val['width']) ? 0 : $val['width'] * $wdt;
                                        ?>
                                            <div class="gantt-chart" style="top:<?= $top - 5;?>px;left:<?= $left ;?>%;">
                                                <div class="gantt-planning-chart planning" style="width:<?= $width ;?>%;background: <?= $val['color'] ?>;">
                                                    &nbsp;
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>

                                <?php
                                    foreach ($data['gantt_actual'] as $key => $val) {
                                        $top = empty($val['top']) ? 0 : $val['top'] * 50;
                                        $left = empty($val['left']) ? 0 : $val['left'] * $wdt;

                                        $width = empty($val['width']) ? 0 : $val['width'] * $wdt;
                                        ?>
                                            <div class="gantt-chart" style="top:<?= $top + 4 ;?>px;left:<?= $left ;?>%;">
                                                <div class="gantt-label-chart actual" style="width:<?= $width ;?>%;background: <?= $val['color'] ?>;">
                                                    <label><?= $val['label'] ;?></label>
                                                    <span><?= $val['note'] ;?></span>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        <?php
    }

    public static function _script(){
        ?>
            <script type="text/javascript">
                setInterval(time_absen,1000);

                function time_absen(){
                    var j;var m; var d;
                    var waktu = new Date();

                    j = waktu.getHours().toString();
                    if(j.length<2){
                        j = '0'+j;
                    }

                    m = waktu.getMinutes().toString();
                    if(m.length<2){
                        m = '0'+m;
                    }
/*
                    d = waktu.getSeconds().toString();
                    if(d.length<2){
                        d = '0'+d;
                    }
*/
                    var wkt = j+':'+m;
 
                    $('#time-daily').html(wkt);
                }
            </script>
        <?php
    }
}