<?php namespace content_shortcuts; ?>
<?php require_once(dirname(__FILE__) . "/../../admin/pg-common.php"); ?>
<?php

// This is a handoff to another loginHandler.  It will do the checking for logged in and offering a form if not.
// Because admin authority is something different from a user, some pages permit admin without login.
// The three arrays below are based on what the loginHandler supports.

// Next we register caller based needs by assigning placing selected programs in one of two arrays.

   if (!isset($admin_ok_noHeader)) {
      $admin_ok_noHeader = array();
   } 
	
   if (!isset($admin_ok_yesHeader)) {
      $admin_ok_yesHeader = array();
   }

   if (!isset($admin_required)) {
      $admin_required = array();
   }


//   $admin_ok_noHeader[] = "/index.php";

//   $admin_ok_yesHeader[] = "/search";
   
     $admin_required[] = dirname($_SERVER['PHP_SELF']) . "/cardUpload.php";

// Now handoff to another loginHandler.
checkLoginHandler();

// When we return, we can map which $_SESSION variables our module needs based on what the handler returns.

// $_SESSION['pgcs_user'] = $_SESSION['username'];
// $_SESSION['pgcs_userid'] = $_SESSION['user_id'];
// $_SESSION['pgcs_db'] = $_SESSION['default_index'];
// $_SESSION['custom_setting'] = "We don't manage login.";
