var wysiwyg_editors = [];

jQuery(document).ready(function(e){
	initWysi();
});

function initWysi(){
	var $ = jQuery;
	return;
	jQuery(".wysi-textarea").each(function(e){

		if(!$(this).hasClass("wysi-active")){

			$(this).addClass("wysi-active");
			
			var events = {
					"change": function() { 
						//console.log("Loaded! "+txt.val() );
					}
				};

			var options = {
				"font-styles" : false,
				"html" : true,
				"image" : false,
				"link" : true,
				"indent":false,
				"events" :events

			}

			var id = $(this).attr("id");
			var editor = $(this).wysihtml5(options).data("wysihtml5").editor;
			//console.log("editor");
			wysiwyg_editors.push({"id":id,"editor":editor});

			var toolbar = $(this).parent().find(".wysihtml5-toolbar");
			//console.log(toolbar.get(0));
			var l = toolbar.find("a.wysi-edit");
			l.on("click",function(e){
				//console.log("clickey  ");
				var link_btn = toolbar.parent().find(".btn.create-link");
				link_btn.toggleClass("disabled");
			});

		}
		//var editor = $(this).wysihtml5(options);
	});
}