<?php
/*
 * 2011/09/09 16:45
 * MultiMenu class function
 * copyright(c) Yoshi Sakai at Bluemoon inc 2011
 * GPL ver3.0 All right reserved.
 */
class getMultiMenu {
  var $block = array();
  
  private $db;
  private $user;
  private $mdl;

  function __construct() {
  	$root =& XCube_Root::getSingleton();

  	$this->user =& $root->mContext->mXoopsUser;
  	$this->db   =& $root->mController->mDB;
  	if (is_object($root->mContext->mModule)) {
  		$this->mdl  =& $root->mContext->mModule->mXoopsModule;
  	} else {
  		$this->mdl = NULL;
  	}
  }
  
  function getblock( $options, $db_name  ) {

	$myts =& MyTextSanitizer::getInstance();
	$block = array();
	$inum = 0;
	$group = is_object($this->user) ? $this->user->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
	$db = $this->db->prefix( $db_name );
	$sql = "SELECT id, groups, link, title, target FROM ".$db." WHERE hide=0 ORDER BY weight ASC";
	$result = $this->db->query($sql);
	$parent_active = false;
	while ( $myrow = $this->db->fetchArray($result) ) {
		//$title = $myts->makeTboxData4Show($myrow["title"]);
		$title = $myts->stripSlashesGPC($myrow["title"]);	// by bluemoon
		if ( !XOOPS_USE_MULTIBYTES ) {
			if (strlen($myrow['title']) >= $options[0]) {
				$title = $myts->makeTboxData4Show(substr($myrow['title'],0,($options[0]-1)))."...";
			}
		}
		$title = preg_replace("/\[XOOPS_URL\]/",XOOPS_URL,$title);
		$myrow['link'] = preg_replace("/\[XOOPS_URL\]/",XOOPS_URL,$myrow['link']);
		$myrow['link'] = $this->replace_userinfo($myrow['link']);
		$groups = explode(" ",$myrow['groups']);
		if (count(array_intersect($group,$groups)) > 0) {
			$imenu = array();
			$imenu['id'] = $myrow['id'];
			$imenu['title'] = $title;
			$imenu['target'] = $myrow['target'];
			$imenu['sublinks'] = array();
			$imenu['link'] = '';
			$mid = 0;
			$head = $myrow['link'][0];
			switch($head) {
				case ' ':
				case '-':
					// hacked by nobunobu start
					$link =  substr($myrow['link'], 1);
					$isub = (isset($block['contents']))? count($block['contents'][$inum-1]['sublinks']) : 0;
					if ($parent_active) {
						$block['contents'][$inum-1]['sublinks'][$isub]['name'] = $title;
						if (preg_match('/^\[([a-z0-9_\-]+)\](.*)$/i', $link, $moduledir)) {
							$module_handler = & xoops_gethandler( 'module' );
							$module =& $module_handler->getByDirname($moduledir[1]);
							if ( is_object( $module ) && $module->getVar( 'isactive' ) ) {
								$link = XOOPS_URL."/modules/".$moduledir[1]."/".$moduledir[2];
							}
						}
						$block['contents'][$inum-1]['sublinks'][$isub]['url'] = $link;
					}
					continue 2;
					// hacked by nobunobu end
					break;
				case '[':
					// [module_name]xxxx.php?aa=aa&bb=bb
					if (preg_match('/^\[([a-z0-9_\-]+)\](.*)$/i', $myrow['link'], $moduledir)) {
						$module_handler = & xoops_gethandler( 'module' );
						$module =& $module_handler->getByDirname($moduledir[1]);
						if ( is_object( $module ) && $module->getVar( 'isactive' ) ) {
							$mid = $module->getVar('mid');
							$imenu['link'] = XOOPS_URL."/modules/".$moduledir[1]."/".$moduledir[2];
							$parent_active = true;
						}
					}
					break;
				case '+':
					// +[module_name]xxxx.php?aa=aa&bb=bb	view submenu
					if (preg_match('/^\+\[([a-z0-9_\-]+)\](.*)$/i', $myrow['link'], $moduledir)) {
						$module_handler = & xoops_gethandler( 'module' );
						$module =& $module_handler->getByDirname($moduledir[1]);
						if ( is_object( $module ) && $module->getVar( 'isactive' ) ) {
							$imenu['link'] = XOOPS_URL."/modules/".$moduledir[1]."/".$moduledir[2];
							$parent_active = true;

							$mid = $module->getVar('mid');
							$sublinks =& $module->subLink();
							if (count($sublinks) > 0)  {
								foreach($sublinks as $sublink){
									if ( !XOOPS_USE_MULTIBYTES ) {
										if (strlen($sublink['name']) >= $options[0]) {
											$sublink['name'] = $myts->makeTboxData4Show(substr($sublink['name'],0,($options[0]-1)))."...";
										}
									}
									$imenu['sublinks'][] = array('name' => $sublink['name'], 'url' => XOOPS_URL.'/modules/'.$moduledir[1].'/'.$sublink['url'] );
								}
							}
						}
					}
					break;
				case '@':
					if (preg_match('/^\@\[([a-z0-9_\-]+)\](.*)$/i', $myrow['link'], $moduledir)) {
						$module_handler = & xoops_gethandler( 'module' );
						$module =& $module_handler->getByDirname($moduledir[1]);
						if ( is_object( $module ) && $module->getVar( 'isactive' ) ) {
							$imenu['link'] = XOOPS_URL."/modules/".$moduledir[1]."/".$moduledir[2];

							$mid = $module->getVar('mid');
							$sublinks =& $module->subLink();

							// hacked by nobunobu start
							if ( (!empty($this->mdl)) && ($moduledir[1] == $this->mdl->get('dirname')) ){
								$parent_active = true;
								if (count($sublinks) > 0) {
									foreach($sublinks as $sublink){
										if ( !XOOPS_USE_MULTIBYTES ) {
											if (strlen($sublink['name']) >= $options[0]) {
												$sublink['name'] = $myts->makeTboxData4Show(substr($sublink['name'],0,($options[0]-1)))."...";
											}
										}
										$imenu['sublinks'][] = array('name' => $sublink['name'], 'url' => XOOPS_URL.'/modules/'.$moduledir[1].'/'.$sublink['url'] );
									}
								}
							} else {
								$parent_active = false;
							// hacked by nobunobu end
							}
						}
					}
					break;
				case '&':
					// &[module_name]xxxx.php?aa=aa&bb=bb	view submenu // hacked by nobunobu
					if (preg_match('/^\&\[([a-z0-9_\-]+)\](.*)$/i', $myrow['link'], $moduledir)) {
						$module_handler = & xoops_gethandler( 'module' );
						$module =& $module_handler->getByDirname($moduledir[1]);
						if ( is_object( $module ) && $module->getVar( 'isactive' ) ) {
							$imenu['link'] = XOOPS_URL."/modules/".$moduledir[1]."/".$moduledir[2];

							$mid = $module->getVar('mid');
							if ( (!empty($this->mdl)) && ($moduledir[1] == $this->mdl->get('dirname')) ){
								$parent_active = true;
							} else {
								$parent_active = false;
							}
						}
					}
					break;
				default:
					$imenu['link'] = $myrow['link'];
			}
			if ($imenu['link']) {
				if (substr($imenu['link'], -1) === '/') {
					$imenu['link'] .= 'index.php';
				}
				if ($mid) {
					$imenu['mid'] = $mid;
				}
				$block['contents'][$inum] = $imenu;
				$inum++;
			}
		}
	}
	//var_dump($block);die;
	$this->block = $block;
	return $block;
  }
  function replace_userinfo($str) {
	if (is_object($this->user)) {
		$str = preg_replace("/\[xoops_uid\]/",$this->user->uid(),$str);
	}
	return $str;
  }
  function getModuleConfig( $name, $mid ) {
	$ret = NULL;
	$config_handler =& xoops_gethandler('config');
	$config =& $config_handler->getConfigsByCat(0, $mid);
	if ( isset($config[$name]) ) $ret = preg_split('/,|[\r\n]+/',$config[$name]);
	return $ret;
  }
  function assign_module_css($css_file) {
	$root =& XCube_Root::getSingleton();
	$renderSystem =& $root->getRenderSystem($root->mContext->mBaseRenderSystemName);
	$css_file = preg_replace("/\[XOOPS_URL\]/i",XOOPS_URL,$css_file);
	$header = $renderSystem->mXoopsTpl->get_template_vars('xoops_block_header');
	$header .= '<link rel="stylesheet" type="text/css" media="all" href="'.$css_file.'" />';
	$renderSystem->mXoopsTpl->assign('xoops_block_header', $header);
  }
  function assign_css() {
	$ch=& xoops_gethandler('config');
	$mconf = $ch->getConfigsByDirname('multiMenu');
	$css_file = $mconf['css_file'];
	if (! empty($css_file)) {
		$this->assign_module_css($css_file);
	}
  }
  function theme_menu($modname="multiMenu") {
	$ch=& xoops_gethandler('config');
	$mconf = $ch->getConfigsByDirname($modname);
	$theme_menu = $mconf['theme_menu'];
	return intval($theme_menu);
  }
}
?>