<?php
require "../../../include/cp_header.php";
require ('admin_function.php');
require_once dirname( __FILE__, 2 ) . '/include/gtickets.php' ;

$menu_num = isset($_GET['mnum']) ? sprintf("%02d", (int) $_GET['mnum'] ) : "01" ;

require ('admin_action.php');
