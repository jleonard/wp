<?php
	global $wpdb;
	$table_name = FrogMetabox_get_db_table();

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		FrogMetabox_updateAttachmentForm();
  }

  $all_post_types;
  $all_post_types = get_post_types('','objects');

  $metaboxes = $wpdb->get_results( "SELECT * FROM $table_name" );

 
?>

<div id="FrogMetabox_manage" class='wrap'>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<p class="submit FrogMetaboxes_submit">
    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Update', 'FrogMetaboxes_trdom' ) ?>" />  
  </p>
	<?php 
	foreach($all_post_types as $post_type){
	?>
		<h4><?php echo $post_type->labels->name; ?></h4>
		<ul>
			<?php foreach($metaboxes as $metabox){
			?>
				<li>
					<input type='checkbox'
					name="<?php echo $post_type->name.'_'.$metabox->id; ?>"
					value="1" 
					<?php if(checkAssignmentForPostType($post_type->name,$metabox->id)){ echo ' checked="checked" ';} ?>
					/>
					<?php echo $metabox->display_name; ?>
				</li>
			<?php } ?>
		</ul>
	<?}?>
</form>
</div>

<?php 
	function FrogMetabox_updateAttachmentForm(){
		global $wpdb;
		$table_name = FrogMetabox_get_db_table();
		$assignment_table = FrogMetabox_get_assignment_table();
		$metaboxes = $wpdb->get_results( "SELECT * FROM $table_name" );
		$all_post_types = get_post_types('','objects');
		foreach($metaboxes as $metabox){
			foreach($all_post_types as $post_type){
				if(!empty($_POST[$post_type->name."_".$metabox->id])){
					error_log("checked ".$metabox->name. " for ".$post_type->name);
					$wpdb->insert(
          $assignment_table,
         		array(
         			"metabox_id"=>$metabox->id,
         			"post_type"=>$post_type->name
         		)
        	);
				}else{
					$wpdb->query(
						$wpdb->prepare( 
						"DELETE FROM $assignment_table
		 					WHERE metabox_id = %d
		 					AND post_type = %s",
	        		$metabox->id, $post_type->name 
        		)
					);
				} 

				// get all the pages for this post type and iterate.
				
               
			} // all post types foreach
		}
	}

?>
	

