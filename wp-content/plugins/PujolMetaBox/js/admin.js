(function($){
	$(document).ready(function(e){
		var $page = $("#PujolMetabox_new");
		if($page.length < 1){
			return;
		}
		$type = $("#type");

		$type.on("change",function(e){
			$box_types = $(".box-type");
			$box_types.hide();
			$("."+$type.val()).show("fast");
		});

		$(".button.delete").on("click",function(e){
			var id = $(this).attr('data-id');
			$("#delete_id").val(id);
			$page.find("form").submit();
		});

	});
})(jQuery);

(function($){
	$(document).ready(function(e){
		$(".Pujol_metabox").each(function(e){
			var $instructions = $(this).children("h5.instructions");
			if($instructions.text() <= 0){ $instructions.hide() }

			var $character_limit = $(this).find(".character_limit");

			if($character_limit.length > 0){
				var $textarea = $(this).find(".textarea");
				var $actual = $character_limit.find(".actual");
				var str = $textarea.val();
				var regex = /(<([^>]+)>)/ig;
				str = str.replace(regex,"");
				var len = str.length;
				$actual.text( len );
				evalLimit($character_limit);

				$textarea.on("keyup",function(e){
					var str = $(this).val();
					var regex = /(<([^>]+)>)/ig;
					str = str.replace(regex,"");
					var len = str.length;
					$actual.text( len );
					evalLimit($character_limit);
				});

			}

			function evalLimit($character_limit){
				var $actual = $character_limit.find(".actual");
				var $limit = $character_limit.find(".limit");

				var actual_int = parseInt( $actual.text() );
				var limit_int = parseInt( $limit.text() );

				if( actual_int >  limit_int ){
					$actual.addClass("over");
				}else{
					$actual.removeClass("over");
				}
			}

		});
	});

})(jQuery);