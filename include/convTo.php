<?php

// ---------------------------------------------
// Create To PDF -------------------------------
// ---------------------------------------------
function sobad_convToPdf($args = array()){
	$check = array_filter($args);
	if(empty($check)){
		return '';
	}

	date_default_timezone_set('UTC');

	if(convToPDF=="createpdf"){
		conv_htmlToPDF($args);
	}else if(convToPDF=="mpdf"){
		conv_mPDF($args);
	}else{
		if(is_callable("conv_toPDF")){
			conv_toPDF($args);
		}
	}
}

function conv_htmlToVar($html='',$data='',$object=''){
	ob_start();

	if(is_callable($html)){
		$html($data);
	}

	if(!empty($object)){
		if(class_exists($object)){			
			if(is_callable(array(new $object(),$html))){
				$func = $html;
				$object::{$func}($data);
			}
		}
	}

	$html = ob_get_clean();

	return $html;
}

function conv_htmlToPDF($args=array()){
	ob_start();

	if(development==1){
		echo '<style type="text/css">';
		foreach($args['style'] as $key => $val){
			if(is_callable($val)){
				echo $val();
			}
		}
		echo '</style>';
	}else{
		echo get_style($args['style']);
	}

	if(is_callable($args['html'])){
		$args['html']($args['data']);
	}

	if(isset($args['object'])){
		if(class_exists($args['object'])){
			$object = $args['object'];
			
			if(is_callable(array(new $object(),$args['html']))){
				$func = $args['html'];
				$object::{$func}($args['data']);
			}
		}
	}
	
	$content = ob_get_clean();

	if(development==1){
		return $content;
	}

	$pos = $args['setting']['posisi'];
	$lay = $args['setting']['layout'];
	$nama = $args['name save'];
	
	try{
		$html2pdf = new HTML2PDF($pos, $lay, 'en', true, 'UTF-8',array(0,0,0,0));
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->setTestTdInOnePage(false);
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($nama.".pdf");
	}
	catch(HTML2PDF_exception $e) {
		echo $e;
		exit;
	}
}


function conv_mPDF($args=array()){
	$data = array();
	$html = array();

	$type = gettype($args['html']);
	if($type=='array'){
		foreach ($args['html'] as $key => $val) {
			$object = isset($val[$key]['object'])?$val[$key]['object']:'';
			$html[] = conv_htmlToVar($val[$key]['html'],$val[$key]['data'],$object);
		}
	}else{
		$object = isset($args['object'])?$args['object']:'';
		$html[] = conv_htmlToVar($args['html'],$args['data'],$object);
	}

	if(development==1){
		$content = '';
		foreach ($html as $key => $val) {
			$content .= $val;
		}

		return $content;
	}

	$pos = $args['setting']['posisi'];
	$lay = $args['setting']['layout'];
	$nama = $args['name save'];
	
	try{
		$mpdf = new HTML2PDF($pos, $lay, 'en', true, 'UTF-8',array(0,0,0,0));
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->setTestTdInOnePage(false);
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output($nama.".pdf");

		$mpdf = new \Mpdf\Mpdf([
		    'format'          => '-', // Default Potrait (Landscape : 'A4-L')
		    'mode'            => 'UTF-8', // Unicode
		    'lang'            => 'en', // Language
		    'margin_top'      => isset($margin['top'])?$margin['top']:0,
		    'margin_bottom'   => isset($margin['bottom'])?$margin['bottom']:0,
		    'margin_left'     => isset($margin['left'])?$margin['left']:0,
		    'margin_right'    => isset($margin['right'])?$margin['right']:0,
		]);

		$mpdf->SetFooter($footer);  
		$mpdf->SetDisplayMode('fullwidth');

		foreach ($html as $key => $val) {
			$mpdf->WriteHTML($val);
		}

		$mpdf->Output('Tes.pdf'); // Format Download
	}
	catch(HTML2PDF_exception $e) {
		echo $e;
		exit;
	}
}