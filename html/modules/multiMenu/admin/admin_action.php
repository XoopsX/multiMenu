<?php
/**
 * @package    Multimenu
 * @version    2.3.1
 * @author     Gigamaster, 2020 XCL PHP7
 * @author     Tom Hayakawa
 * @copyright  Copyright 2005-2021 XOOPSCube Project
 * @license    https://github.com/xoopscube/xcl/blob/master/docs/GPL_V2.txt
 */

if ( ! defined( 'XOOPS_ROOT_PATH' ) ) {
	exit();
}

$root     = XCube_Root::getSingleton();
$op       = $root->mContext->mRequest->getRequest( 'op' );
$op       = empty( $op ) ? '' : $op;
$class    = new multimenu( $menu_num );

switch ( $op ) {
	case "new":
		$class->im_admin_new();
		break;
	case "edit":
		$class->im_admin_edit();
		break;
	case "update":
		$class->im_admin_update();
		break;
	case "del":
		$class->im_admin_del();
		break;
	case "move":
		$class->im_admin_move();
		$class->im_admin_list();
		break;
	default:
		$class->im_admin_list();
		break;
}
