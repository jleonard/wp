<?php

/*

Plugin Name: Pujol MetaBox
Plugin URI: http://www.Pujoldesign.com
Description: A plugin for creating and assigning metaboxes
Author: Pujol
Version: 0.3 
Author URI: http://www.Pujoldesign.com 


- 4 types
 -> textarea (l-m)
 -> input
 -> date
 -> date range
 -> select
 -> radio


 Pujol_metaboxes

 id 
 name
 display
 metabox_type
 instructions
 character_limit (for textareas)
 default_text_value
 defaut_checked_item
 page_position
 placeholder_text


 Pujol_metabox_assignments

*/

 	function PujolMetabox_get_db_table(){
		//global $wpdb;
		return "Pujol_metaboxes";
	}

	function PujolMetabox_register_capabilities(){
      do_action('PujolRoles_register_capability','create_Pujol_metaboxes',"Edit Metaboxes","","Advanced");
      do_action('PujolRoles_register_capability','assign_Pujol_metaboxes',"Assign Metaboxes","","Advanced");
  }
  add_action("wp_loaded","PujolMetabox_register_capabilities");

	function PujolMetaboxes_plugin_install(){
 
	  global $wpdb;
	  global $wp_roles;
	  
	  $table_name = PujolMetabox_get_db_table();

	  $sql = "CREATE TABLE $table_name (
	    id mediumint(9) NOT NULL AUTO_INCREMENT,
	    character_limit text NULL,
	    default_checked_item mediumint(9) NULL DEFAULT 0,
	    display_name text NULL,
	    instructions text NULL,
	    metabox_type text NULL,
	    name tinytext NULL,
	    select_options text NULL,
	    page_position text NULL,
	    placeholder_text text NULL,
	    textarea_size text NULL,
	    UNIQUE KEY id (id)
	    );";

	  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	  dbDelta($sql);
	}
	register_activation_hook(__FILE__,'PujolMetaboxes_plugin_install');

	function PujolMetaboxes_menu_page(){
  	include("include/metabox_admin.php");
	}
	function PujolMetaboxes_assign_page(){
		include("include/metabox_assignments.php");
	}
	function PujolMetaboxes_admin_actions() {
		//if(current_user_can("create_Pujol_metaboxes")){
			add_menu_page( "Create_Metaboxes", "Create Metaboxes", 1, "PujolMetaboxes_slug", "PujolMetaboxes_menu_page" );
		//}
		//if(current_user_can("assign_Pujol_metaboxes")){
			add_menu_page( "Assign_Metaboxes", "Assign Metaboxes", 1, "PujolMetaboxes_assign_slug", "PujolMetaboxes_assign_page" );
		//}
	}  
	add_action('admin_menu', 'PujolMetaboxes_admin_actions');

	function PujolMetaboxes_admin_scripts_method(){
		
		wp_register_script('PujolMetaboxes_js',plugins_url("js/admin.js",__FILE__ ));
		wp_enqueue_script("PujolMetaboxes_js");

		wp_register_script('PujolMetaboxes_assignments_js',plugins_url("js/assignments.js",__FILE__ ));
		wp_enqueue_script("PujolMetaboxes_assignments_js");

		wp_register_script('PujolMetaboxes_wysiwyg_init_js',plugins_url("js/wysiwyg-init.js",__FILE__ ));
		wp_enqueue_script("PujolMetaboxes_wysiwyg_init_js");

		wp_register_style( 'PujolMetaboxes_css', plugins_url("style.css",__FILE__ ), false, '1.0.0' );
		wp_enqueue_style( 'PujolMetaboxes_css' );

	}

	add_action('admin_enqueue_scripts', 'PujolMetaboxes_admin_scripts_method',2);


	function PujolMetaboxes_createFormName($str){
		$str = trim($str);
		$name = str_replace(" ","_",$str);
  		$name = ereg_replace("[^A-Za-z._]", "", $name );
  		$sql_name = str_replace(".","",$name);
  		return strtolower($sql_name);
	}

	function PujolMetaboxes_renderboxes(){
		global $wpdb;
		global $post;
		$post_type = $post->post_type;
		$table_name = PujolMetabox_get_db_table();
		$metaboxes = $wpdb->get_results( "SELECT * FROM $table_name" );
		//add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
		foreach($metaboxes as $metabox){

			$applied_to_all = get_option("apply_to_all_".$post->post_type."_".$metabox->id);
			$has = get_post_meta($post->ID,"Pujol_metabox_".$metabox->id,true);
			
			if($applied_to_all || !empty($has)){
				$_title = stripslashes($metabox->display_name);
				add_meta_box($metabox->name,$_title,"PujolMetaboxes_metabox",$post->post_type,$metabox->page_position,'default',array("metabox"=>$metabox));
			}

		}
	}
	add_action('add_meta_boxes', 'PujolMetaboxes_renderboxes');

	function PujolMetaboxes_metabox($post,$args){
		$_args = $args["args"];
  		$mb = $_args["metabox"];
	  	switch($mb->metabox_type){
	  		case "textarea":
	  			include("include/textarea.php");
	  		break;
	  		case "input":
	  			include("include/input.php");
	  		break;
	  		case "radio":
	  			include("include/radio.php");
	  		break;
	  		case "select":
	  			include("include/select.php");
	  		break;
	  		case "date":
	  		 	include("include/date.php");
	  		break;
	  		case "number":
	  			include("include/input_number.php");
	  		break;
	  		case "checkbox":
	  			include("include/checkbox.php");
	  		break;
	  	}
	}

	/* insert post hook */
function PujolMetaboxes_insert_post_hook($id,$post){
  
	  if($post->post_status == "inherit"){
	      return;
	  }
	  
	  global $wpdb;
	  global $post;

	  $table_name = PujolMetabox_get_db_table();
	  $metaboxes = $wpdb->get_results( "SELECT * FROM $table_name" );

	  $cache = wp_cache_get( "post_object" );
	  if( !$cache ){
	    $cache = array();
	  }

	  foreach($metaboxes as $metabox){
	    
	  	if(!empty($_POST[$metabox->name])){
	  		$clean = stripslashes($_POST[$metabox->name]);
	  		update_post_meta($post->ID,$metabox->name,$clean);
	    	$cache[$metabox->name] = $clean;
	    }else{
	    	delete_post_meta($post->ID,$metabox->name);
	    }
	  }
	  wp_cache_set('post_object', $cache);
	}
	add_action("wp_insert_post", "PujolMetaboxes_insert_post_hook", 5, 2 );

	function _randomID($len){
  		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  		$string = '';
		 for ($i = 0; $i < $len; $i++) {
		      $string .= $characters[rand(0, strlen($characters) - 1)];
		 }
		 return $string;
  	}

?>