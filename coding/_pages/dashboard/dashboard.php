<?php

(!defined('DEFPATH')) ? exit : '';

class dashboard_system
{
    public function _reg()
    {
        $GLOBALS['body'] = 'dashboard';
        self::_script();
    }

    public function _page($args = [])
    {
        $data = dashboard_it::index();
        theme_layout('load_here', $data);
    }

    private function _script()
    {
        $script = new vendor_script();

		// url script jQuery - Vendor
		$get_jquery = $script->_get_('_js_core',array('jquery-core'));
		$head[0] = '<script src="'.$get_jquery['jquery-core'].'"></script>';
		$head[1] = '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>';

		// url script css ----->
		$css = array_merge(
				$script->_get_('_css_global'),
				$script->_get_('_css_page_level',array('bootstrap-datepicker','bootstrap-clockpicker','fullcalender','bootstrap-editable')),
				$script->_get_('_css_contextmenu'),
				$script->_get_('_css_chart'),
				$script->_get_('_css_datatable',array('datatable'))
			);
		
		// url script css ----->
		$js = array_merge(
				$script->_get_('_js_core'),
				$script->_get_('_js_page_level',array('bootstrap-toastr','bootstrap-datepicker','bootstrap-clockpicker')),
				$script->_get_('_js_contextmenu'),
				$script->_get_('_js_tags_input'),
				$script->_get_('_js_chart')
			);

		unset($js['jquery-core']);

//		$custom['login'] = self::load_script();

		reg_hook("reg_script_head",$head);
		reg_hook("reg_script_css",$css);
		reg_hook("reg_script_js",$js);
//		reg_hook("reg_script_foot",$custom);
    }

    private function load_script(){

	}
}
