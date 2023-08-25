<?php

(!defined('THEMEPATH')) ? exit : '';

abstract class dashboard_template
{
	protected static function label_progress($data = []){
		?>
			<div class="label-chart">
				<span><?= $data['label'] ;?></span>
				<div class="square-color" style="background-color: <?= $data['color'] ;?>;"></div>
				<label><?= $data['qty'] ;?></label>
			</div>
		<?php
	}

	public static function profile_user($data = []){
		$src = $data['url'] . '/' . $data['picture'];

		?>
			<div class="image-profile">
				<div class="row">
					<div class="col-md-4">
						<img src="<?= $src ;?>" style="width:80%;">
					</div>
					<div class="col-md-8">
						<label><?= $data['name'];?></label>
					</div>
				</div>
			</div>
		<?php
	}

	public static function image_profile($data = []){
		$src = $data['url'] . '/' . $data['picture'];

		?>
			<div class="image-profile">
				<div class="row">
					<div class="col-md-3">
						<div style="display: grid;justify-content: space-between;text-align:center;">
		                    <div class="bag-profile-user">
		                        <img src="<?= $src ;?>" style="width:100%;">
		                    </div>
		                </div>
					</div>
					<div class="col-md-8">
						<div class="name-profile">
							<label><?= $data['name'];?></label>
						</div>
						<span><?= $data['jobtitle'];?></span>
					</div>
				</div>
			</div>
		<?php
	}

	public static function table_content($data = []){
		?>
			<table class="table-content">
				<thead>
					<tr>
						<?php
							foreach ($data['thead'] as $key => $val) {
								echo '<th class="head-team" style="width:'.$val['width'].';text-align:'.$val['align'].'">'.$val['label'].'</th>';
							}
						?>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($data['tbody'] as $key => $val) {
							$baris = '';
							foreach ($val as $ky => $vl) {
								$baris .= '<td class="body-team" style="text-align:'.$vl[0].'">'.$vl[1].'</td>';
							}

							echo '<tr>'.$baris.'</tr>';
						}
					?>
				</tbody>
			</table>
		<?php
	}
}