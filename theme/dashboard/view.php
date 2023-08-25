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
    			<div class="row">
    				<?php
	    				foreach ($data as $key => $val) {
	    					$func = $val['func'];
	    					$args = $val['data'];

	    					echo '<div class="col-md-'.$val['col'].'">';
	    					if (is_callable(array(new self(), $func))) {
								self::{$func}($args);
							}
	    					echo '</div>';
	    				}
    				?>
    			</div>
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
    				<div class="col-md-4">
    					<div class="label-progress">
    						<?php
    							$xvalue = $yvalue = $color = [];
	    						foreach ($data['label'] as $key => $val) {
	    							$xvalue[] = '"'.$val['label'].'"';
	    							$yvalue[] = $val['qty'];
	    							$color[] = '"'.$val['color'].'"';

	    							self::label_progress($val);
	    						}

	    						$xvalue = implode(',', $xvalue);
	    						$yvalue = implode(',', $yvalue);
	    						$color = implode(',', $color);
    						?>
    					</div>
    				</div>
    				<div class="col-md-8">
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
    			
    			<label><?= $data['title'] ;?></label>
    			<span><?= $data['jobdesk'] ;?></span>
    			
    			<div class="date-project">
    				<div class="square-color">
    					<label>PLAN</label>
    				</div>
    				<div class="square-color start-date">
    					<span></span>
    					<label><?= $data['start_date'] ;?></label>
    				</div>
    				<div class="square-color finish-date">
    					<span></span>
    					<label><?= $data['finish_date'] ;?></label>
    				</div>
    			</div>

    			<div class="date-project">
    				<div class="square-color">
    					<label>ACT</label>
    				</div>
    				<div class="square-color start-date">
    					<span></span>
    					<label><?= $data['start_actual'] ;?></label>
    				</div>
    				<div class="square-color finish-date">
    					<span></span>
    					<label><?= $data['finish_actual'] ;?></label>
    				</div>
    			</div>

    			<div class="progress-project">
    				<div class="progress-box">
    					<div class="progress-bar" style="width:<?= $data['progress_width'] ;?>;"></div>
    				</div>
    				<span><?= $data['progress'] ;?></span>
    			</div>

    			<div class="workday-project">
    				<span>Work Day: <strong><?= $data['worktime'] ;?></strong></span>
    			</div>
    		</div>
    	<?php
    }
}