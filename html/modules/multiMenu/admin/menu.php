<?php
/**
 * @package    Multimenu
 * @version    2.3.1
 * @author     Gigamaster, 2020 XCL PHP7
 * @author     Domifara, v 1.20 2012/01/21
 * @author     Tom Hayakawa
 * @copyright  Copyright 2005-2021 XOOPSCube Project
 * @license    https://github.com/xoopscube/xcl/blob/master/docs/GPL_V2.txt
 */

if ( ! defined( 'XOOPS_ROOT_PATH' ) ) {
	exit();
}
$adminmenu[0]['title'] = _IM_MULTIMENU_NAME;
$adminmenu[0]['link']  = "admin/index.php?mnum=1";
$adminmenu[1]['title'] = _IM_MULTIMENU_NAME_1;
$adminmenu[1]['link']  = "admin/index.php?mnum=2";
$adminmenu[2]['title'] = _IM_MULTIMENU_NAME_2;
$adminmenu[2]['link']  = "admin/index.php?mnum=3";
$adminmenu[3]['title'] = _IM_MULTIMENU_NAME_3;
$adminmenu[3]['link']  = "admin/index.php?mnum=4";
$adminmenu[4]['title'] = _IM_MULTIMENU_NAME_4;
$adminmenu[4]['link']  = "admin/index.php?mnum=5";
$adminmenu[5]['title'] = _IM_MULTIMENU_NAME_5;
$adminmenu[5]['link']  = "admin/index.php?mnum=6";
$adminmenu[6]['title'] = _IM_MULTIMENU_NAME_6;
$adminmenu[6]['link']  = "admin/index.php?mnum=7";
$adminmenu[7]['title'] = _IM_MULTIMENU_NAME_7;
$adminmenu[7]['link']  = "admin/index.php?mnum=8";
$adminmenu[8]['title'] = _IM_MULTIMENU_FLOW;
$adminmenu[8]['link']  = "admin/index.php?mnum=99";

// link to myblocksadmin.php add by Tom Thanks GIJ
$adminmenu[9]['title'] = _IM_MULTIMENU_NAME_BL;
$adminmenu[9]['link']  = "admin/myblocksadmin.php";
