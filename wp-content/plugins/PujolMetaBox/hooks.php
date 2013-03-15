<?php
	global $wpdb;

	function PujolMetaBox_insert_metabox_hook(){

		$table_name = PujolMetabox_get_db_table();
		$name = PujolMetaboxes_createFormName($_POST["name"]);
		$select_options = PujolMetabox_sanitizeCSV($_POST["select_options"]);

		$arr = array( 
          'page_position' => $_POST["page_position"],
          'name' => $name,
          'display_name'=> $_POST["display_name"],
          'select_options'=>$select_options,
          'character_limit'=>$_POST["character_limit"],
          'instructions'=>$_POST["instructions"],
          'textarea_size'=>$_POST["textarea_size"],
          'placeholder_text'=>$_POST["placeholder_text"]
        );

		$result = $wpdb->insert(
      $table_name,
      $arr
    );

    echo 1;
		die(); // this is required to return a proper result

	}
	add_action('wp_ajax_Pujol_create_metabox', 'PujolMetaBox_insert_metabox_hook');

?>