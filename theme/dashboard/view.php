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
								$func = $val['func'];
								$args = $val['data'];
	
								echo '<div id class="col-md-'.$val['col'].'">';
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
    }

    public static function project_team($data = []){
    	?>
    		<div class="project-team-it box-content">
    			<?php self::table_content($data) ?>
    		</div>
    	<?php
    }

    public static function progress_team($data = []){
    	?>
    		<div class="progress-team-it box-content">
    			<div class="row">
    				<div class="col-md-12">
    					<label class="title-progress"><?= $data['title'] ;?></label>
    				</div>
    				<div class="col-md-12">
                        <?php
                            $xvalue = $yvalue = $color = [];
                            foreach ($data['label'] as $key => $val) {
                                $xvalue[] = '"'.$val['label'].'"';
                                $yvalue[] = $val['qty'];
                                $color[] = '"'.$val['color'].'"';
                            }

                            $xvalue = implode(',', $xvalue);
                            $yvalue = implode(',', $yvalue);
                            $color = implode(',', $color);
                        ?>

    					<canvas id="chart-progress"></canvas>
    					<script type="text/javascript">
    						var xValues = [<?= $xvalue;?>];
							var yValues = [<?= $yvalue;?>];
							var barColors = [<?= $color;?>];

    						new Chart("chart-progress", {
							  type: "doughnut",
							  data: {
							    labels: xValues,
							    datasets: [{
							      backgroundColor: barColors,
							      data: yValues
							    }]
							  },
							  options: {
						         legend: {
						            display: false
						         },
						    	}
							});
    					</script>
    				</div>
                    <div class="col-md-12">
                        <div class="label-progress">
                            <?php
                                foreach ($data['label'] as $key => $val) {
                                    self::label_progress($val);
                                }
                            ?>
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
    			<?php self::table_content($data['table']) ?>
    		</div>
    	<?php
    }

    public static function project_user($data = []){
    	?>
    		<div class="project-user-it box-content">
    			<div class="project-total">
    				<label><?= $data['total'] ;?></label>
    			</div>

    			<?php self::image_profile($data) ;?>
    			
    			<label class="task-label"><?= $data['title'] ;?></label>
    			<label class="role-label"><?= $data['jobdesk'] ;?></label>
    			
    			<div class="date-project">
    				<div class="square-color" style="background: linear-gradient(126.69deg, #EFDCFF 28.65%, #E2BAFA 84.56%);color: #9D28DD;">
    					<label class="plan">PLAN</label>
    				</div>
    				<div class="square-color start-date">
    					<span>Start Date</span>
    					<label><?= $data['start_date'] ;?></label>
    				</div>
    				<div class="square-color finish-date">
    					<span>Finish Date</span>
    					<label><?= $data['finish_date'] ;?></label>
    				</div>
    			</div>

    			<div class="date-project">
    				<div class="square-color" style="background: linear-gradient(126.69deg, #EFDCFF 28.65%, #E2BAFA 84.56%);color: #9D28DD;">
    					<label class="plan">ACT</label>
    				</div>
    				<div class="square-color start-date">
    					<span>Start Date</span>
    					<label><?= $data['start_actual'] ;?></label>
    				</div>
    				<div class="square-color finish-date">
    					<span>Finish Date</span>
    					<label><?= $data['finish_actual'] ;?></label>
    				</div>
    			</div>

    			<div class="progress-project">
					<div class="progress-block">
						<div class="progress-bar" style="width: <?= $data['progress'] ;?>"></div>
					</div>
					<label><?= $data['progress'] ;?></label>
    			</div>

    			<div class="workday-project">
    				<p style="text-align: center;">Work Day: <strong><?= $data['worktime'] ;?></strong></p>
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
                                </div>
                                <div class="date-week-year">
                                    <div class="bag-month">&nbsp;</div>
                                    <label>Week <?= $data['week_month'] ;?></label>
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
                                            <div class="gantt-chart" style="top:<?= $top ;?>px;left:<?= $left ;?>%;">
                                                <div class="gantt-label-chart planning" style="width:<?= $width ;?>%;background: <?= $val['color'] ?>;">
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
}