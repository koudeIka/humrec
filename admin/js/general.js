
/****************************************************************************************************************/
/********************* -- BIBLIOTHEQUE (awesome) -- ******************************************/
/****************************************************************************************************************/

function biblioPlugin(options)
{
	mybiblioPlugin = this;
	mybiblioPlugin.rel_id = "";
	mybiblioPlugin.art_id = "";
	mybiblioPlugin.new_id = "";
	mybiblioPlugin.return_function = "";
	mybiblioPlugin.ajax_file = "";
	mybiblioPlugin.ajax_file_action = "";
	mybiblioPlugin.page_width = 822;
	mybiblioPlugin.fancybox = true;
	mybiblioPlugin.debug = false;



	if ( typeof biblioPlugin.initialized == "undefined" )
	{
		/* ###################### ACTIONS SUR LES IMAGES ########################## */
		biblioPlugin.prototype.mainEngine = function() {
					$('#dialog_biblio .biblio_wrapper')
						.undelegate('.biblio_page ul li', "mouseenter mouseleave")
						.delegate('.biblio_page ul li', "mouseenter mouseleave", function(event) {
							switch(event.type)
							{
								case "mouseenter":
									$(this).prepend("<div class='add_from_biblio ui-icon ui-icon-circle-plus'>&nbsp;</div>");
									break;

								case "mouseleave":
									$(this).children(".add_from_biblio").remove();
									break;
							}
						})
						.undelegate('a', "click")
						.delegate('a', "click", function() {
							if(mybiblioPlugin.fancybox=== true)
							{
								$.fancybox({
									"transitionIn"	:	"elastic",
									"transitionOut"	:	"elastic",
									"speedIn"		:	600,
									"speedOut"	:	200,
									"href"		: this.href
								});
							}
							return false;
						})
						.undelegate('.add_from_biblio', "click")
						.delegate('.add_from_biblio', "click", function() {
								var img_id = $(this).closest("li").data("id");
								mybiblioPlugin.return_function(img_id);
						});
		};

		/* ###################### PAGINATION ########################## */
		biblioPlugin.prototype.paginationEngine = function() {
					var $current_page = "";
					$('#dialog_biblio .biblio_pagination')
						.undelegate('.biblio_pagination-prev', "click")
						.delegate('.biblio_pagination-prev', "click", function() {
								$current_page = $('#dialog_biblio .current_page');
								if ($current_page.val() > 1)
								{
									mybiblioPlugin.goToPrevious($current_page);
								}
						})
						.undelegate('.current_page', "keypress")
						.delegate('.current_page', "keypress", function(e) {
								$current_page = $('#dialog_biblio .current_page');
								if ((e.keyCode == 13) || (e.which == 13))
								{
									mybiblioPlugin.goToPage($current_page);
								}
						})
						.undelegate('.biblio_pagination-next', "click")
						.delegate('.biblio_pagination-next', "click", function() {
								$current_page = $('#dialog_biblio .current_page');
								if (parseInt($current_page.val(), 10) < parseInt($('#dialog_biblio .max_page').text(), 10))
								{
									mybiblioPlugin.goToNext($current_page);
								}
						});
		};

		/* ###################### MINI MOTEUR DE RECHERCHE ########################## */
		biblioPlugin.prototype.searchEngine = function() {
					var $input_search = "";
					$('#reset_biblio_search')
						.button("destroy")
						.button({
							text: true,
							icons: {
								primary: "ui-icon-arrowreturnthick-1-w"
							}
						});
					$('#dialog_biblio .biblio_search')
						.undelegate('#input_biblio_search', "keypress")
						.delegate('#input_biblio_search', "keypress", function(e) {
								$input_search = $('#input_biblio_search');
								if ((e.keyCode == 13) || (e.which == 13))
								{
									mybiblioPlugin.goFind($input_search);
								}
						})
						.undelegate('#reset_biblio_search', "click")
						.delegate('#reset_biblio_search', "click", function() {
								$input_search = $('#input_biblio_search');
								$input_search.val("");
								mybiblioPlugin.goFind($input_search);
						});
		};

		/* ###################### CHARGER UNE PAGE ########################## */
		biblioPlugin.prototype.loadPage = function(page_num, type) {
				if (mybiblioPlugin.debug === true) { console.log("Je charge la page "+page_num); }
				$.ajax({
					type: "POST",
					url: mybiblioPlugin.ajax_file,
				        data: {
						action: mybiblioPlugin.ajax_file_action,
						art_id: mybiblioPlugin.art_id,
						rel_id: mybiblioPlugin.rel_id,
						new_id: mybiblioPlugin.new_id,
						page_num: page_num,
						type: type,
						search: $('#dialog_biblio #input_biblio_search').val()
					},
					success: function(data) {
						switch(type)
						{
							case "previous":
								$('#dialog_biblio .biblio_img').prepend(data);
								break;

							case "pageCurrent":
								$('#dialog_biblio .biblio_img').children(':eq(1)').replaceWith(data);
								$('#ajax_loader_big').hide();
								break;

							case "pagePrevious":
								$('#dialog_biblio .biblio_img').children(':eq(0)').replaceWith(data);
								break;

							case "pageNext":
								$('#dialog_biblio .biblio_img').children(':eq(2)').replaceWith(data);
								break;

							case "searchCurrent":
								$('#dialog_biblio .biblio_img').children(':eq(1)').replaceWith(data.split(":::")[0]);
								$('#dialog_biblio .biblio_pagination-page').html(data.split(":::")[1]);
								$('#ajax_loader_big').hide();
								break;

							case "searchNext":
								$('#dialog_biblio .biblio_img').children(':eq(2)').replaceWith(data);
								$('#ajax_loader_big').hide();
								break;

							case "next":
								$('#dialog_biblio .biblio_img').append(data);
								break;
						}
					}
				});
		};

		/* ###################### GOTO ########################## */
		biblioPlugin.prototype.goToPrevious = function($current_page) {
					var page_prev = parseInt($current_page.val(), 10)-1;
					$current_page.val(page_prev);
					if (mybiblioPlugin.debug === true) { console.log("J'affiche "+$current_page.val()); }
					mybiblioPlugin.loadPage((parseInt(page_prev, 10)-1), "previous");
					mybiblioPlugin.animateTo("left");


		};
		biblioPlugin.prototype.goToPage = function($current_page) {
					var pos = $current_page.offset();
					var width = $current_page.width();
					$('#ajax_loader_big').css( { "left": (pos.left + width -60) + "px", "top":(pos.top - 350) + "px", "z-index": "2000" } ).show();
					var page_current = $current_page.val();
					console.log("J'affiche "+$current_page.val());
					mybiblioPlugin.loadPage(page_current, "pageCurrent");
					mybiblioPlugin.loadPage((parseInt(page_current, 10)-1), "pagePrevious");
					mybiblioPlugin.loadPage((parseInt(page_current, 10)+1), "pageNext");

		};
		biblioPlugin.prototype.goFind = function($input_search) {
					var pos = $input_search.offset();
					var width = $input_search.width();
					$('#ajax_loader_big').css( { "left": (pos.left + width -60) + "px", "top":(pos.top - 350) + "px", "z-index": "2000" } ).show();
					$('#dialog_biblio .biblio_img').children(":eq(0)").empty();

					mybiblioPlugin.loadPage(1, "searchCurrent");
					mybiblioPlugin.loadPage(2, "searchNext");
					mybiblioPlugin.animateTo("begin");

		};
		biblioPlugin.prototype.goToNext = function($current_page) {
					var page_next = parseInt($current_page.val(), 10)+1;
					$current_page.val(page_next);
					console.log("J'affiche "+$current_page.val());
					mybiblioPlugin.loadPage((parseInt(page_next, 10)+1), "next");
					mybiblioPlugin.animateTo("right");
		};

		/* ###################### ANIMATION ########################## */
		biblioPlugin.prototype.animateTo = function(direction) {
					var $biblio_img = $('#dialog_biblio .biblio_img');
					var current_margin = $biblio_img.css("margin-left");
					switch(direction)
					{
						case "left":
							var previous_margin = parseInt(current_margin.split("p")[0], 10)+(parseInt(mybiblioPlugin.page_width, 10));
							$biblio_img.animate({
									marginLeft: previous_margin+"px"
								}, 800, function() {
									$biblio_img
										.css("margin-left", current_margin)
										.children(":last").remove();
								});
							break;

						case "right":
							var next_margin = parseInt(current_margin.split("p")[0], 10)-(parseInt(mybiblioPlugin.page_width, 10));
							$biblio_img.animate({
									marginLeft: next_margin+"px"
								}, 800, function() {
									$biblio_img
										.css("margin-left", current_margin)
										.children(":first").remove();
								});
							break;

						case "begin":
							$biblio_img.css("margin-left", "-"+mybiblioPlugin.page_width+"px");
							break;
					}
		};

		/* ###################### CHARGEMENT POPUP ########################## */
		biblioPlugin.prototype.loadPopup = function() {
					$('#dialog_biblio')
						.unbind()
						.empty()
						.dialog({
							title: "Biblioth&egrave;que d'images",
							width: 835,
							position: ["center","top"],
							buttons: {
								"Fermer" :  function() {
									$(this).dialog("close");
								}
							}
						})
						.bind("dialogclose", function(event, ui) {
							$('#dialog_biblio').empty();
						})
						.bind("dialogopen", function(event, ui) {
							mybiblioPlugin.mainEngine();
							mybiblioPlugin.paginationEngine();
							mybiblioPlugin.searchEngine();
							mybiblioPlugin.animateTo("begin");
						})
						.load(
							mybiblioPlugin.ajax_file,
							{
								action: mybiblioPlugin.ajax_file_action,
								step: "initialisation",
								rel_id: mybiblioPlugin.rel_id,
								new_id: mybiblioPlugin.new_id,
								art_id: mybiblioPlugin.art_id
							},
							function() {
								$('#dialog_biblio').dialog("open");
							}
						);
		};

		/* ###################### INITIALISATION ########################## */
		biblioPlugin.prototype.init = function() {
					mybiblioPlugin.loadPopup();
		};
		biblioPlugin.initialized = true;
		mybiblioPlugin.rel_id = options.rel_id;
		mybiblioPlugin.art_id = options.art_id;
		mybiblioPlugin.new_id = options.new_id;
		mybiblioPlugin.return_function = options.return_function;
		mybiblioPlugin.ajax_file = options.ajax_file;
		mybiblioPlugin.ajax_file_action = options.ajax_file_action;
		mybiblioPlugin.page_width = 822;
		mybiblioPlugin.fancybox = options.fancybox;
		mybiblioPlugin.debug = options.debug;
	}
}


/* AJAX LOADER GIF */
function waitingGif() { }
waitingGif.open = function(options) {
		$('#ajax_loader_'+options.type).css({ "left": (options.left) + "px", "top":(options.top) + "px" }).show();
};
waitingGif.close = function(options) {
		if (typeof options.type == "undefined")
		{
			$('#ajax_loader_small, #ajax_loader_big').hide();
		} else
		{
			$('#ajax_loader_'+options.type).hide();
		}
};



var delay = (function(){
	var timer = 0;
	return function(callback, ms){
	clearTimeout (timer);
		timer = setTimeout(callback, ms);
	};
})();