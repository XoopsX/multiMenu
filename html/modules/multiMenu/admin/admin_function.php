<?php
/**
 * @package    Multimenu
 * @version    2.3.1
 * @author     Gigamaster, 2020 XCL PHP7
 * @author     Domifara
 * @author     Tom Hayakawa
 * @copyright  Copyright 2005-2021 XOOPSCube Project
 * @license    https://github.com/xoopscube/xcl/blob/master/docs/GPL_V2.txt
 */

if ( ! defined( 'XOOPS_ROOT_PATH' ) ) {
	exit();
}

class multimenu {

	private $mnum;

	private $db;

	private $root;

	public $menu_num;

	public function __construct( $menu_num = '01' ) {

		$this->root = XCube_Root::getSingleton();
		$this->mnum = $menu_num;
		$this->db   = XoopsDatabaseFactory::getDatabaseConnection();
	}

	public function mm_admin_menu( $currentoption = 0, $breadcrumb = "" ) {
		$tblColors                   = array();
		$tblColors[0]                = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = $tblColors[99] = 'unselected';
		$tblColors[ $currentoption ] = 'selected';

		echo '<table width=100% class="outer">
        <tr><td align="right">
          <b>' . $this->root->mContext->mModule->mXoopsModule->getShow( 'name' ) . ' : ' . $breadcrumb . '</b>
          </td></tr></table><br>';

		echo '<nav class="adminavi">';
		echo '<a href="index.php?mnum=1" class="adminavi-item ' . $tblColors[1] . '">' . _AD_MULTIMENU_ADMIN_01 . '</a>';
		echo '<a href="index.php?mnum=2" class="adminavi-item ' . $tblColors[2] . '">' . _AD_MULTIMENU_ADMIN_02 . '</a>';
		echo '<a href="index.php?mnum=3" class="adminavi-item ' . $tblColors[3] . '">' . _AD_MULTIMENU_ADMIN_03 . '</a>';
		echo '<a href="index.php?mnum=4" class="adminavi-item ' . $tblColors[4] . '">' . _AD_MULTIMENU_ADMIN_04 . '</a>';

		echo '<a href="index.php?mnum=5" class="adminavi-item ' . $tblColors[5] . '">' . _AD_MULTIMENU_ADMIN_05 . '</a>';
		echo '<a href="index.php?mnum=6" class="adminavi-item ' . $tblColors[6] . '">' . _AD_MULTIMENU_ADMIN_06 . '</a>';
		echo '<a href="index.php?mnum=7" class="adminavi-item ' . $tblColors[7] . '">' . _AD_MULTIMENU_ADMIN_07 . '</a>';
		echo '<a href="index.php?mnum=8" class="adminavi-item ' . $tblColors[8] . '">' . _AD_MULTIMENU_ADMIN_08 . '</a>';

		echo '<a href="index.php?mnum=99" class="adminavi-item ' . $tblColors[99] . '">' . _AD_MULTIMENU_ADMIN_99 . '</a>';

		echo '<a href="myblocksadmin.php" class="adminavi-item ' . $tblColors[0] . '">' . _AM_BADMIN . '</a>';

		echo '<a href="' . XOOPS_MODULE_URL . '/legacy/admin/index.php?action=PreferenceEdit&confmod_id=' . $this->root->mContext->mModule->mXoopsModule->get( 'mid' ) . '"
    class="adminavi-item">' . _PREFERENCES . '</a>';
		echo '<a href="' . XOOPS_MODULE_URL . '/legacy/admin/index.php?action=Help&dirname=multiMenu" class="adminavi-item">' . _HELP . '</a>';
		echo '</nav>';
	}

	private function im_admin_clean() {
		global $xoopsDB;
		$i      = 0;
		$db     = $xoopsDB->prefix( "multimenu" . $this->menu_num );
		$result = $xoopsDB->query( "SELECT id FROM " . $db . " ORDER BY weight ASC" );
		while ( list( $id ) = $xoopsDB->fetchrow( $result ) ) {
			$xoopsDB->queryF( "UPDATE " . $db . " SET weight='$i' WHERE id=$id" );
			$i ++;
		}
	}

	public function im_admin_list() {
		xoops_cp_header();
		$this->mm_admin_menu( (int) $this->mnum, _AD_MULTIMENU_ADMIN . $this->mnum );

		echo '<h2>' . _AD_MULTIMENU_ADMIN . $this->mnum . '</h2>';
		echo '<form action="index.php?mnum=' . $this->mnum . '&op=new" method="post" name="form1">
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="outer">
    <tr>
    <th align="center">' . _AD_MULTIMENU_TITLE . '</th>
    <th align="center">' . _AD_MULTIMENU_HIDE . '</th>
    <th align="center">' . _AD_MULTIMENU_LINK . '</th>
    <th align="center">' . _AD_MULTIMENU_OPERATION . '</th>
    </tr>';

		$modhand = xoops_getmodulehandler( 'menu' );
		$modhand->setTable( $this->mnum );
		$mCriteria = new CriteriaCompo();
		$mCriteria->addSort( 'weight' );
		$modhand->im_admin_clean();
		$objcts = $modhand->getObjects( $mCriteria );
		$class  = 'even';

// XCL admin action icons by gigamaster
		$mIconView = '<img class="svg visible" src="' . XOOPS_URL . '/images/icons/view.svg" width="1em" alt="'. _NO.'">';
		$mIconViewNo = '<img class="svg visible" src="' . XOOPS_URL . '/images/icons/invisible.svg" width="1em" alt="'. _YES.'">';

		$mIconEdit = '<img class="svg visible" src="' . XOOPS_URL . '/images/icons/edit.svg" width="1em" alt="' . _EDIT . '">';
		$mIconDown = '<img class="svg visible" src="' . XOOPS_URL . '/images/icons/arrow-down.svg" width="1em" alt="' . _AD_MULTIMENU_DOWN . '">';
		$mIconUp = '<img class="svg visible" src="' . XOOPS_URL . '/images/icons/arrow-up.svg" width="1em" alt="' . _AD_MULTIMENU_UP . '">';
		$mIconDelete = '<img class="svg visible" src="' . XOOPS_URL . '/images/icons/delete.svg" width="1em" alt="' . _DELETE . '">';


		foreach ( $objcts as $obj ) {
			if ( $obj->get( 'weight' ) != 0 ) {
				$moveup = "<a href='index.php?mnum=" . $this->mnum . "&op=move&id=" . $obj->get( 'id' ) . "&weight=" . ( $obj->get( 'weight' ) - 1 ) . "' title='" . _AD_MULTIMENU_UP . "'>$mIconUp</a>";
			} else {
				$moveup = "<span style='color:darkorange;' title='" . _AD_MULTIMENU_UP . "'>$mIconUp</span>";
			}
			if ( $obj->get( 'weight' ) != ( count( $objcts ) - 1 ) ) {
				$movedown = "<a href='index.php?mnum=" . $this->mnum . "&op=move&id=" . $obj->get( 'id' ) . "&weight=" . ( $obj->get( 'weight' ) + 2 ) . "' title='" . _AD_MULTIMENU_DOWN . "'>$mIconDown</a>";
			} else {
				$movedown = "<span style='color:darkorange;' title='" . _AD_MULTIMENU_DOWN . "'>$mIconDown</span>";
			}
			// fix by domifara Notice [PHP]: Undefined variable: status
			// XCL admin action icons by gigamaster
			// $status = $obj->get( 'hide' ) ? _YES : _NO;
			$status = $obj->get( 'hide' ) ? $mIconViewNo : $mIconView;

			echo "<tr>
        <td class='$class'>" . $obj->get( 'title' ) . "</td>
        <td class='$class' align='center'>$status</td>
        <td class='$class'>" . $obj->get( 'link' ) . "</td>
        <td class='$class' align='center'>
		<a href='index.php?mnum=" . $this->mnum . "&op=edit&id=" . $obj->get( 'id' ) . "' title='" . _EDIT . "'>$mIconEdit</a>
		" . $moveup . $movedown . "
        <a href='index.php?mnum=" . $this->mnum . "&op=del&id=" . $obj->get( 'id' ) . "' title='" . _DELETE . "'>$mIconDelete</a>
        
        </td></tr>";
			$class = ( $class == 'odd' ) ? 'even' : 'odd';
		}

		echo "<tr><td class='foot' colspan='4' align='right'>";

		echo $GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ );
		//echo "<input type='button' name='cancel' value='" . _CANCEL. "'>
		echo "<input type='submit' name='submit' value='" . _AD_MULTIMENU_NEW . "'>
    </td></tr></table></form>";

		xoops_cp_footer();
	}

	public function im_admin_new() {
		if ( ! $GLOBALS['xoopsGTicket']->check() ) {
			redirect_header( 'index.php', 3, $GLOBALS['xoopsGTicket']->getErrors() );
		}
		global $xoopsDB;
		xoops_cp_header();
		$this->mm_admin_menu( (int) $this->mnum, _AD_MULTIMENU_ADMIN . $this->mnum );
		echo "<h2>" . _AD_MULTIMENU_ADMIN . $this->mnum . "</h2>";

		$id             = 0;
		$title          = '';
		$link           = '';
		$hide           = '';
		$weight         = 191;
		$target         = "_self";
		$member_handler = xoops_gethandler( 'member' );
		$xoopsgroups    = $member_handler->getGroups();
		$count          = count( $xoopsgroups );
		$groups         = array();
		for ( $i = 0; $i < $count; $i ++ ) {
			$groups[] = $xoopsgroups[ $i ]->getVar( 'groupid' );
		}
		include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
		$form = new XoopsThemeForm( _AD_MULTIMENU_NEWIMENU, "newform", "index.php?mnum=" . $this->mnum );

		$formtitle = new XoopsFormText( _AD_MULTIMENU_TITLE, "title", 50, 150, "" );
		$formlink  = new XoopsFormText( _AD_MULTIMENU_LINK, "link", 50, 191, "" );
		$formhide  = new XoopsFormSelect( _AD_MULTIMENU_HIDE, "hide", "" );
		$formhide->addOption( "0", _NO );
		$formhide->addOption( "1", _YES );
		$formtarget = new XoopsFormSelect( _AD_MULTIMENU_TARGET, "target", "_self" );
		$formtarget->addOption( "_self", _AD_MULTIMENU_TARG_SELF );
		$formtarget->addOption( "_blank", _AD_MULTIMENU_TARG_BLANK );
		$formtarget->addOption( "_parent", _AD_MULTIMENU_TARG_PARENT );
		$formtarget->addOption( "_top", _AD_MULTIMENU_TARG_TOP );
		$formgroups    = new XoopsFormSelectGroup( _AD_MULTIMENU_GROUPS, "groups", true, $groups, 5, true );
		$submit_button = new XoopsFormButton( "", "submit", _AD_MULTIMENU_SUBMIT, "submit" );

		$form->addElement( $formtitle, true );
		$form->addElement( $formlink, false );
		$form->addElement( $formhide );
		$form->addElement( $formtarget );
		$form->addElement( $formgroups );

		$formHiddenID = new XoopsFormHidden( "id", 0 );
		$form->addElement( $formHiddenID );

		$formHiddenOP = new XoopsFormHidden( "op", "update" );
		$form->addElement( $formHiddenOP  );

		$form->addElement( $submit_button );

//for gticket by domifara
		$GLOBALS['xoopsGTicket']->addTicketXoopsFormElement( $form, __LINE__ );

		$form->display();
	
		xoops_cp_footer();
	}

	private function im_admin_update_menu( &$obj ) {
		$title  = isset( $_POST['title'] ) ? $this->root->mContext->mRequest->getRequest( 'title' ) : 'NoTitle';
		$link   = isset( $_POST['link'] ) ? $this->root->mContext->mRequest->getRequest( 'link' ) : 'https://github.com/xoopscube/xcl';
		$hide   = empty( $_POST['hide'] ) ? 0 : 1;
		$groups = isset( $_POST['groups'] ) ? $this->root->mContext->mRequest->getRequest( 'groups' ) : '';
		$groups = ( is_array( $groups ) ) ? implode( " ", array_map( 'intval', $groups ) ) : '';
		$target = isset( $_POST['target'] ) ? $this->root->mContext->mRequest->getRequest( 'target' ) : '_self';
		//$obj->set('id', $id);
		$obj->set( 'title', $title );
		$obj->set( 'hide', $hide );
		$obj->set( 'link', $link );
		$obj->set( 'target', $target );
		$obj->set( 'groups', $groups );
	}

	public function im_admin_update() {
		if ( ! $GLOBALS['xoopsGTicket']->check() ) {
			redirect_header( 'index.php', 3, $GLOBALS['xoopsGTicket']->getErrors() );
		}
		$modhand = xoops_getmodulehandler( 'menu' );
		$modhand->setTable( $this->mnum );
		$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		if ( $id == 0 ) {
			$obj = $modhand->create();
		} else {
			$obj = $modhand->get( $id );
		}
		$this->im_admin_update_menu( $obj );
		$success = $modhand->insert( $obj );
		if ( ! $success ) {
			redirect_header( "index.php?mnum=" . $this->mnum, 2, _AD_MULTIMENU_UPDATED );
		} else {
			$modhand->im_admin_clean();
			redirect_header( "index.php?mnum=" . $this->mnum, 2, _AD_MULTIMENU_UPDATED );
		}
		exit();
	}

	public function im_admin_edit() {
		xoops_cp_header();
		$this->mm_admin_menu( (int) $this->mnum, _AD_MULTIMENU_ADMIN . $this->mnum );
		echo "<h2>" . _AD_MULTIMENU_ADMIN . $this->mnum . "</h2>";

		$id      = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		$modhand = xoops_getmodulehandler( 'menu' );
		$modhand->setTable( $this->mnum );
		$obj = $modhand->get( $id );

		$groups = explode( " ", $obj->get( 'groups' ) );
		include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
		$form      = new XoopsThemeForm( _AD_MULTIMENU_EDITIMENU, "editform", "index.php?mnum=" . $this->mnum );
		$formtitle = new XoopsFormText( _AD_MULTIMENU_TITLE, "title", 50, 150, $obj->get( 'title' ) );
		$formlink  = new XoopsFormText( _AD_MULTIMENU_LINK, "link", 50, 191, $obj->get( 'link' ) );
		/*
		 * for future request
		 if ($this->mnum=="99"){
			$block_id  = new XoopsFormText(_AD_MULTIMENU_BLOCKID , "block_id" , 5, 5, $obj->get('block_id'));
			$parent_id = new XoopsFormText(_AD_MULTIMENU_PARENTID, "parent_id", 5, 5, $obj->get('parent_id'));
		}
		 */
		$formhide = new XoopsFormSelect( _AD_MULTIMENU_HIDE, "hide", $obj->get( 'hide' ) );
		$formhide->addOption( "0", _NO );
		$formhide->addOption( "1", _YES );
		$formtarget = new XoopsFormSelect( _AD_MULTIMENU_TARGET, "target", $obj->get( 'target' ) );
		$formtarget->addOption( "_self", _AD_MULTIMENU_TARG_SELF );
		$formtarget->addOption( "_blank", _AD_MULTIMENU_TARG_BLANK );
		$formtarget->addOption( "_parent", _AD_MULTIMENU_TARG_PARENT );
		$formtarget->addOption( "_top", _AD_MULTIMENU_TARG_TOP );
		$formgroups    = new XoopsFormSelectGroup( _AD_MULTIMENU_GROUPS, "groups", true, $groups, 5, true );
		$submit_button = new XoopsFormButton( "", "submit", _AD_MULTIMENU_SUBMIT, "submit" );
		//$cancel_button = new XoopsFormButton( "", "cancel", _AD_MULTIMENU_CANCEL, "cancel" );

		$form->addElement( $formtitle, true );
		$form->addElement( $formlink, false );
		$form->addElement( $block_id, false );
		$form->addElement( $parent_id, false );
		$form->addElement( $formhide );
		$form->addElement( $formtarget );
		$form->addElement( $formgroups );

		$formHiddenID = new XoopsFormHidden( "id", $id );
		$form->addElement( $formHiddenID );

		$formHiddenOP = new XoopsFormHidden( "op", "update" );
		$form->addElement( $formHiddenOP  );

		$form->addElement( $submit_button );
		//$form->addElement( $cancel_button );

//for gticket by domifara
		$GLOBALS['xoopsGTicket']->addTicketXoopsFormElement( $form, __LINE__ );

		$form->display();

		xoops_cp_footer();
	}

	public function im_admin_del() {
		$del = isset( $_POST['del'] ) ? 1 : 0;
		$id  = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;

		if ( $del == 1 ) {
			if ( ! $GLOBALS['xoopsGTicket']->check() ) {
				redirect_header( 'index.php', 3, $GLOBALS['xoopsGTicket']->getErrors() );
			}
			$id      = isset( $_POST['id'] ) ? (int) $_POST['id'] : $id;
			$modhand = xoops_getmodulehandler( 'menu' );
			$modhand->setTable( $this->mnum );
			$obj = $modhand->get( $id );

			if ( $modhand->delete( $obj ) ) {
				$modhand->im_admin_clean( $this->mnum );
				redirect_header( "index.php?mnum=" . $this->mnum, 2, _AD_MULTIMENU_UPDATED );
			} else {
				redirect_header( "index.php?mnum=" . $this->mnum, 2, _AD_MULTIMENU_NOTUPDATED );
			}
			exit();
		} else {
			xoops_cp_header();
			echo "<h4>" . _AD_MULTIMENU_ADMIN . $this->mnum . "</h4>";
			xoops_confirm( array(
				               'op'  => 'del',
				               'id'  => $id,
				               'del' => 1
			               ) + $GLOBALS['xoopsGTicket']->getTicketArray( __LINE__ ), 'index.php?op=del&mnum=' . $this->mnum, _AD_MULTIMENU_SUREDELETE );
			xoops_cp_footer();
		}
	}

	public function im_admin_move() {
		$id     = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		$weight = isset( $_GET['weight'] ) ? (int) $_GET['weight'] : 0;
		$db     = $this->db->prefix( "multimenu" . $this->mnum );
		$this->db->queryF( "UPDATE `" . $db . "` SET `weight` = `weight` + 1 WHERE `weight` >= " . $weight . " AND `id` <> " . $id );
		$this->db->queryF( "UPDATE `" . $db . "` SET `weight` = " . $weight . " WHERE `id` = " . $id );
		$modhand = xoops_getmodulehandler( 'menu' );
		$modhand->im_admin_clean( $this->mnum );
	}
}

