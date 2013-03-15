<?php
	global $post;
	$_args = $args["args"];
  	$mb = $_args["metabox"];

  	$randomId = _randomId(5);
?>
<div class="Pujol_metabox">
<h5 class='instructions'><?php echo $mb->instructions; ?></h5>


<?php 
	//error_log( $mb->name );
	//echo print_r($mb,true);
	$css = "";
	if($mb->name != "headline"){
		$css = "wysi-textarea show-create-link";
	}
?>


<textarea id="textarea-<?php echo $randomId; ?>" class="textarea  <?php echo $css . " ". $mb->textarea_size; ?>" name="<?php echo $mb->name; ?>"><?php echo get_post_meta($post->ID,$mb->name,true); ?></textarea>


<?php if(!empty($mb->character_limit)){ ?>

<div class="character_limit">
	<p>Character limit</p>
	<div class="pill">
		<p class="actual"></p>
		<p class="limit"><?php echo $mb->character_limit; ?></p>
	</div>
</div>


<?php } ?>

</div>