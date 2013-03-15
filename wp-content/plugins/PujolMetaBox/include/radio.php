<?php 
	global $post;
	$_args = $args["args"];
  $mb = $_args["metabox"];
  $options = explode(",",$mb->select_options);
?>
<div class="Pujol_metabox">
<h5 class='instructions'><?php echo $mb->instructions; ?></h5>

<?php for($i = 0; $i < count($options); $i++){?>
	<input type='radio' 
		name="<?php echo $mb->name; ?>"
		value="<?php echo $options[$i]; ?>"
		<?php 
			$pm = get_post_meta($post->ID,$mb->name,true);
			if($pm == $options[$i]){
				echo " checked='checked' ";
			}
		?> 
		/>&nbsp;&nbsp;<?php echo $options[$i]; ?><br/> 
<?php } ?>
</div>