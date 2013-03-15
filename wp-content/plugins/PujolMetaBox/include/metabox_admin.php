<?php

	global $wpdb; 
	$table_name = PujolMetabox_get_db_table();

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		PujolMetabox_delete();
    PujolMetabox_evaluateNewForm();
    PujolMetabox_updateForm();
  }

	$metaboxes = $wpdb->get_results( "SELECT * FROM $table_name" );

	$metabox_types = array(
			array("name"=>"Textarea","type"=>"textarea"),
			array("name"=>"Text Input","type"=>"input"),
			array("name"=>"Checkbox","type"=>"checkbox"),
			array("name"=>"Radio Buttons","type"=>"radio"),
			array("name"=>"Select (dropdown)","type"=>"select"),
			array("name"=>"Date","type"=>"date"),
			array("name"=>"Number","type"=>"number")
	);

	$page_positions = array(
		array("name"=>"Normal","val"=>"normal"),
		array("name"=>"Sidebar","val"=>"side")
	);

	$textarea_sizes = array(
		array("name"=>"Large","val"=>"l"),
		array("name"=>"Small","val"=>"s")
	);

?>
<div id="PujolMetabox_new" class='wrap'>

	<?php echo "<h2>" . __( 'Manage Metaboxes', 'PujolMetaboxes_trdom' ) . "</h2>"; ?>


	<form class="well" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

		<input id="delete_id" name="delete_id" val="" type="hidden"/>

	<table class='table table-bordered table-striped table-condensed'>
		<thead>
			<tr>
				<td>Title</td>
				<td>Form name</td>
				<td>Type</td>
				<td>Page position</td>
				<td>Instructions</td>
				<td>Options</td>
				<td>Textarea size</td>
				<td>Placeholder text</td>
				<td>Char count</td>
				<td>Actions</td>
			</tr>
		</thead>

		<tbody>
			<?php foreach($metaboxes as $metabox){?>
			<tr>
				<td>
					<input 
								type="text" 
								name="mb_<?php echo $metabox->id; ?>_display_name" 
								value="<?php echo stripslashes($metabox->display_name); ?>"/>
				</td>
				<td>
					<input 
								type="text" 
								name="mb_<?php echo $metabox->id; ?>_name" 
								value="<?php echo $metabox->name; ?>"/>
				</td>
				<td><?php echo $metabox->metabox_type; ?></td>
				<td>
					<select name="mb_<?php echo $metabox->id; ?>_page_position">
					<?php 
						for($i = 0 ; $i < count($page_positions); $i++){ 
							echo "<option ";
							if($page_positions[$i]["val"] == $metabox->page_position){
								echo "selected='selected' ";
							}
							echo "value ='".$page_positions[$i]["val"]."'>";
							echo $page_positions[$i]["name"];
							echo "</option>";
						 }
					?>
					</select> 
				</td>
				<td>
					<textarea name="mb_<?php echo $metabox->id; ?>_instructions"><?php echo $metabox->instructions; ?></textarea>
				</td>
				<td>
					<textarea name="mb_<?php echo $metabox->id; ?>_select_options"><?php echo $metabox->select_options; ?></textarea>
				</td>
				<td>
					<select name="mb_<?php echo $metabox->id; ?>_textarea_size">
					<?php 
						for($i = 0 ; $i < count($textarea_sizes); $i++){ 
							echo "<option ";
							if($textarea_sizes[$i]["val"] == $metabox->textarea_size){
								echo "selected='selected' ";
							}
							echo "value ='".$textarea_sizes[$i]["val"]."'>";
							echo $textarea_sizes[$i]["name"];
							echo "</option>";
						 }
					?>
					</select> 
				</td>
				<td>
					<input type="text" name="mb_<?php echo $metabox->id; ?>_placeholder_text" 
					value="<?php echo $metabox->placeholder_text; ?>"/>
				</td>
				<td>
					<input type="number" min="1" name="mb_<?php echo $metabox->id; ?>_character_limit" 
					value="<?php echo $metabox->character_limit; ?>"/>
				</td>
				<td>
					<div class="button delete" data-id="<?php echo $metabox->id; ?>"><span class="icon icon-remove"></span>Delete</div>
				</td>
			</tr>
			<?php } ?>
		</tbody>

	</table>

	<p class="submit PujolMetaboxes_submit">
       <input type="submit" name="Submit" class="button-primary" value="Update" />  
    </p>

		<div>
		<label>Type of Metabox</label>
		<select id="type" name="type">
			<option value="ignore">Choose one:</option>
			<?php for($i = 0; $i < count($metabox_types); $i++){?>
				<option 
					value="<?php echo $metabox_types[$i]['type']; ?>">
					<?php echo $metabox_types[$i]['name']; ?>
				</option>
			<?php } ?>
		</select>
		</div>

		<p class="submit PujolMetaboxes_submit">
       <input type="submit" name="Submit" class="button-primary" value="<?php _e('Add', 'PujolMetaboxes_trdom' ) ?>" />  
    </p>

	</form>

</div>

<?php
	function PujolMetabox_evaluateNewForm(){
		global $wpdb;
		$table_name = PujolMetabox_get_db_table();
		$display_name = $_POST["name"];
		$type = $_POST["type"];
		if($type == "ignore"){
			return;
		}
		
		$name = PujolMetaboxes_createFormName($display_name);
		
		$arr =  array(
			'metabox_type'=>$type,
    );

		$result = $wpdb->insert(
      $table_name,
      $arr
    );
	}

	function PujolMetabox_updateForm(){
		
		global $wpdb;
		$table_name = PujolMetabox_get_db_table();
		$metaboxes = $wpdb->get_results( "SELECT * FROM $table_name" );

		foreach ($metaboxes as $metabox) {
			
			$id = $metabox->id;
			$prefix = "mb_".$metabox->id."_";
			$name = PujolMetaboxes_createFormName($_POST[$prefix."name"]);
			$select_options = PujolMetabox_sanitizeCSV($_POST[$prefix."select_options"]);
			
			$wpdb->update( 
        $table_name, 
        array( 
          'page_position' => $_POST[$prefix."page_position"],
          'name' => $name,
          'display_name'=>$_POST[$prefix."display_name"],
          'select_options'=>$select_options,
          'character_limit'=>$_POST[$prefix."character_limit"],
          'instructions'=>$_POST[$prefix."instructions"],
          'textarea_size'=>$_POST[$prefix."textarea_size"],
          'placeholder_text'=>$_POST[$prefix."placeholder_text"]
        ), 
        array( 'id' => $id )
      );

		}
	}

	function PujolMetabox_sanitizeCSV($str){
		$arr = explode(",",$str);
		$clean = array();
		for($i = 0; $i < count($arr); $i++){
			$t = trim($arr[$i]);
			array_push($clean,$t);
		}
		return implode(",",$clean);
	}

	function PujolMetabox_delete(){
		global $wpdb;
		$table_name = PujolMetabox_get_db_table();
		if(!empty($_POST["delete_id"])){
			$wpdb->query( 
      	$wpdb->prepare( 
      		"
           DELETE FROM $table_name
      		 WHERE id = %d
      		 LIMIT 1
      		",
      	        $_POST["delete_id"]
              )
      );
		}
	}
?>