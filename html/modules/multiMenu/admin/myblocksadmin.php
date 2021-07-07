<?php
/**
 * @package    Multimenu
 * @version    2.3.1
 * @author     Gigamaster, 2020 XCL PHP7
 * @author     Tom Hayakawa
 * @copyright  Copyright 2005-2021 XOOPSCube Project
 * @license    https://github.com/xoopscube/xcl/blob/master/docs/GPL_V2.txt
 */

include_once( '../../../include/cp_header.php' );
include_once( 'mygrouppermform.php' );
include_once( XOOPS_ROOT_PATH . '/class/xoopsblock.php' );

// check $xoopsModule
if ( ! is_object( $xoopsModule ) ) {
	redirect_header( XOOPS_URL . '/user.php', 1, _NOPERM );
}

// get blocks owned by the module
// $block_arr =& XoopsBlock::getByModule( $xoopsModule->mid() ) ; @gigamaster make call dynamic
$block_arr =& (new XoopsBlock)->getByModule( $xoopsModule->mid() ) ;

// add by Tom
sort( $block_arr );
reset( $block_arr );

function list_blocks() {
	global $xoopsUser, $xoopsConfig, $xoopsDB;
	global $block_arr;

	$side_descs = array( 0 => _AM_SBLEFT, 1 => _AM_SBRIGHT, 3 => _AM_CBLEFT, 4 => _AM_CBRIGHT, 5 => _AM_CBCENTER );

	// displaying TH
	echo "
	<table width='100%'>
	<tr valign='middle'>
	<th width='20%'>" . _AM_BLKDESC . "</th>
	<th>" . _AM_TITLE . "</th>
	<th align='center' nowrap='nowrap'>" . _AM_SIDE . "</th>
	<th align='center'>" . _AM_WEIGHT . "</th>
	<th align='center'>" . _AM_VISIBLE . "</th>
	<th align='right'>" . _AM_ACTION . "</th>
	</tr>
	";

	// blocks displaying loop
	if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$blockAdmin        = XOOPS_URL . "/modules/legacy/admin/index.php?action=BlockEdit&amp;bid=";
		$blockInstallAdmin = XOOPS_URL . "/modules/legacy/admin/index.php?action=BlockInstallEdit&amp;bid=";
	}
	$iconVisible = '<img class="svg visible" src="' . XOOPS_URL . '/images/icons/view.svg" width="1em" alt="'. _NO.'">';
	$iconInvisible = '<img class="svg visible" src="' . XOOPS_URL . '/images/icons/invisible.svg" width="1em" alt="'. _YES.'">';


	$mBlockEdit = '<img class="svg edit-box" src="' . XOOPS_URL . '/images/icons/edit.svg" width="1em" alt="' . _INSTALL . '">';
	$mBlockInstall = '<img class="svg visible" src="' . XOOPS_URL . '/images/icons/block-install.svg" width="1em" alt="' . _EDIT . '">';

	$class = 'even';
	foreach ( array_keys( $block_arr ) as $i ) {
		$visible   = ( $block_arr[ $i ]->getVar( "visible" ) == 1 ) ? $iconVisible : $iconInvisible;
		$weight    = $block_arr[ $i ]->getVar( "weight" );
		$side_desc = $side_descs[ $block_arr[ $i ]->getVar( "side" ) ];
		$title     = $block_arr[ $i ]->getVar( "title" );
		if ( $title == '' ) {
			$title = "&nbsp;";
		}
		$name = $block_arr[ $i ]->getVar( "name" );
		$bid  = $block_arr[ $i ]->getVar( "bid" );

		echo "<tr valign='top'>
		<td class='$class'>$name</td>
		<td class='$class'>$title</td>
		<td class='$class' align='center'>$side_desc</td>
		<td class='$class' align='center'>$weight</td>
		<td class='$class' align='center' nowrap>$visible</td>
		<td class='$class' align='right'>";
		if ( $visible === $iconVisible ) {
			echo "<a href='$blockAdmin$bid' title=" . _EDIT . ">$mBlockEdit</a>";
		} else {
			echo "<a href='$blockInstallAdmin$bid' title=" . _INSTALL . ">$mBlockInstall</a>";
		}
		echo "</td>
		</tr>\n";

		$class = ( $class == 'even' ) ? 'odd' : 'even';
	}
	echo "<tr><td class='foot' align='center' colspan='7'>
	</td></tr></table>\n";
}


function list_groups() {
	global $xoopsUser, $xoopsConfig, $xoopsDB;
	global $xoopsModule, $block_arr;

	foreach ( array_keys( $block_arr ) as $i ) {
		$item_list[ $block_arr[ $i ]->getVar( "bid" ) ] = $block_arr[ $i ]->getVar( "title" );
	}

	$form = new MyXoopsGroupPermForm( '', 1, 'block_read', _MD_AM_ADGS );
	$form->addAppendix( 'module_admin', $xoopsModule->mid(), $xoopsModule->name() . ' ' . _AM_ACTIVERIGHTS );
	$form->addAppendix( 'module_read', $xoopsModule->mid(), $xoopsModule->name() . ' ' . _AM_ACCESSRIGHTS );
	foreach ( $item_list as $item_id => $item_name ) {
		$form->addItem( $item_id, $item_name );
	}
	echo $form->render();
}


if ( ! empty( $_POST['submit'] ) ) {
	include( "mygroupperm.php" );
	redirect_header( XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/admin/myblocksadmin.php", 1, _MD_AM_DBUPDATED );
}

xoops_cp_header();


require 'admin_function.php';

$class = new multimenu( $menu_num );

$class->mm_admin_menu( 0, _AM_BADMIN );

list_blocks();

xoops_cp_footer();
