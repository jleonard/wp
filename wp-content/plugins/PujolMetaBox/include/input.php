<?php
	global $post;
	$_args = $args["args"];
  	$mb = $_args["metabox"];
  	$val = get_post_meta($post->ID,$mb->name,true);
  	//error_log("mb name ".$mb->name);
  	//error_log("value = ".$val);
?>
<div class="Pujol_metabox">
<h5 class='instructions'><?php echo $mb->instructions; ?></h5>
<input 
	type='text'
	name="<?php echo $mb->name; ?>" 
	value='<?php echo get_post_meta($post->ID,$mb->name,true); ?>'
	placeholder= '<?php echo $mb->placeholder_text; ?>'
	 />
</div>