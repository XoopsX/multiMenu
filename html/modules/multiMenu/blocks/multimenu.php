<?php
/*
 * 2011/09/09 16:45
 * Multi-Menu block function
 * copyright(c) Yoshi Sakai at Bluemoon inc 2011
 * GPL ver3.0 All right reserved.
 */

function a_multimenu_show($options) {
	return b_multimenu_show_base($options, '01');
}
function b_multimenu_show($options) {
	return b_multimenu_show_base($options, '02');
}
function c_multimenu_show($options) {
	return b_multimenu_show_base($options, '03');
}
function d_multimenu_show($options) {
	return b_multimenu_show_base($options, '04');
}
function e_multimenu_show($options) {
	return b_multimenu_show_base($options, '05');
}
function f_multimenu_show($options) {
	return b_multimenu_show_base($options, '06');
}
function g_multimenu_show($options) {
	return b_multimenu_show_base($options, '07');
}
function h_multimenu_show($options) {
	return b_multimenu_show_base($options, '08');
}
function flow_menu_show($options) {
	return b_multimenu_show_base($options, '99');
}

function b_multimenu_show_base($options, $num) {
	include_once XOOPS_ROOT_PATH . '/modules/multiMenu/class/getMultiMenu.class.php';
	static $gmm;
	if (! $gmm) {
		$gmm = new getMultiMenu();
		$gmm->assign_css();
	}
	$block = $gmm->getblock( $options, 'multimenu' . $num );
	return $block;
}

function b_multimenu_edit($options) {
	$form = _BM_MULTIMENU_CHARS."&nbsp;<input type='text' name='options[]' value='".$options[0]."' />&nbsp;"._BM_MULTIMENU_LENGTH."";
	return $form;
}
?>