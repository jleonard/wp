(function($){
	$(document).ready(function(e){
		$page = $("#PujolMetabox_manage");
		if($page.length < 1){
			return;
		}

		$("input[type='checkbox']").on("change",function(e){
			if($(this).hasClass("apply-all")){
				var post_type = $(this).attr("data-type");
				var metabox_id = $(this).attr("data-metabox");
				var $apply_all = $(this);
				$(".metabox_check").each(function(e){
					var type = $(this).attr("data-type");
					var metabox = $(this).attr("data-metabox");
					if(type == post_type && metabox == metabox_id){
						$(this).prop("checked",$apply_all.is(":checked"));
					}
				});
			}
		});   

		$("#table-toggle").on("change",function(e){
			var val = $(this).val();
			$("table.table").hide();
			$("#"+val).show("fast");
		});

	}); // end $().ready()
})(jQuery);