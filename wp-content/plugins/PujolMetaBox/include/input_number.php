<?php
	global $post;
	$_args = $args["args"];
  $mb = $_args["metabox"];
?>
<div class="Pujol_metabox">
<h5 class='instructions'><?php echo $mb->instructions; ?></h5>
<input 
	type='number'
	name="<?php echo $mb->name; ?>" 
	value='<?php echo get_post_meta($post->ID,$mb->name,true); ?>'
	 />
</div>