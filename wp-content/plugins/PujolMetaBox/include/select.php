<?php 
	global $post;
	$_args = $args["args"];
  $mb = $_args["metabox"];
  $options = explode(",",$mb->select_options);
?>
<div class="Pujol_metabox">
<h5 class='instructions'><?php echo $mb->instructions; ?></h5>
<select name="<?php echo $mb->name; ?>">
<?php for($i = 0; $i < count($options); $i++){?>
	<option
		value="<?php echo $options[$i]; ?>"
		<?php 
			$pm = get_post_meta($post->ID,$mb->name,true);
			if($pm == $options[$i]){
				echo " selected='selected' ";
			}
		?> 
		><?php echo $options[$i]; ?></option> 
<?php } ?>
</select>
</div>