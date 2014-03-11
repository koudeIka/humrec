

function cardsNew()
{
	mycardsNew = this;
	mycardsNew.new_id = "";

	if ( typeof cardsNew.initialized == "undefined" )
	{
		cardsNew.prototype.save = function(parameters) {
					if ( typeof parameters.new_title != "undefined" )
					{
						$.post(
							"ajax/news.ajax.php",
							{
									action: "save_new_title",
									new_id: mycardsNew.new_id,
									new_title: parameters.new_title
							}
						);
					} else if ( typeof parameters.new_content_fr != "undefined" )
					{
						$.post(
								"ajax/news.ajax.php",
								{
									action: "save_new_content",
									new_id: mycardsNew.new_id,
									new_content_fr: parameters.new_content_fr
								}
							);
					} else if ( typeof parameters.new_content_en != "undefined" )
					{
						$.post(
							"ajax/news.ajax.php",
							{
								action: "save_new_content",
								new_id: mycardsNew.new_id,
								new_content_en: parameters.new_content_en
							}
						);
					} else if ( typeof parameters.new_date != "undefined" )
					{
						$.post(
							"ajax/news.ajax.php",
							{
								action: "save_new_date",
								new_id: mycardsNew.new_id,
								new_date: parameters.new_date
							}
						);
					}
		};

		cardsNew.prototype.date = function() {
					$('#new_edit .new_date input').datepicker({
							dateFormat: "dd/mm/yy",
							onSelect: function(dateText, inst) {
									mycardsNew.save({new_date: dateText});
							}
						});
		};

		cardsNew.prototype.cleditor = function() {
					$('#new_edit .new_content_fr textarea')
						.cleditor({
							width:        660, // width not including margins, borders or padding
							height:       250, // height not including margins, borders or padding
							controls:     // controls to add to the toolbar
								"bold italic underline strikethrough subscript superscript | font size " +
								" | color highlight removeformat | bullets numbering | outdent " +
								"indent | alignleft center alignright justify | " +
								"rule image link unlink ",
						})
						.change(function() {
							var editor = $('#new_edit .new_content_fr textarea').cleditor()[0];
							editor.updateTextArea();
							mycardsNew.save({new_content_fr: $('#new_edit .new_content_fr textarea').val()});
						});
					$('#new_edit .new_content_fr textarea').cleditor()[0].refresh();

					$('#new_edit .new_content_en textarea')
						.cleditor({
							width:        660, // width not including margins, borders or padding
							height:       250, // height not including margins, borders or padding
							controls:     // controls to add to the toolbar
								"bold italic underline strikethrough subscript superscript | font size " +
								" | color highlight removeformat | bullets numbering | outdent " +
								"indent | alignleft center alignright justify | " +
								"rule image link unlink ",
						})
						.change(function() {
							var editor = $('#new_edit .new_content_en textarea').cleditor()[0];
							editor.updateTextArea();
							mycardsNew.save({new_content_en: $('#new_edit .new_content_en textarea').val()});
						});
					$('#new_edit .new_content_en textarea').cleditor()[0].refresh();
		};

		cardsNew.prototype.addPho = function(pho_id) {
					$.ajax({
						type: "POST",
						url	: 	"ajax/news.ajax.php",
					        data : {
							action: "add_pho",
							new_id: mycardsNew.new_id,
							pho_id: pho_id
						},
						success	: 	function(data){
							if ($('#photo_list').length == 0)
							{
							    $('.new_pictures .menu_img').before("<ul id='photo_list'></ul>");
							}
							$('#photo_list')
								.append(data)
								.find("li:last")
								.end()
								.sortable("refresh");
						},
						error	: 	function(msg){ dispayError(msg); }
					});
		};

		cardsNew.prototype.removePho = function(pho_id) {
					$('#photo_list li[data-pho_id="'+pho_id+'"]').slideUp("fast", function() { $(this).remove(); });
					$.ajax({
						type: "POST",
						url	: 	"ajax/news.ajax.php",
					        data : {
							action: "del_pho",
							new_id: mycardsNew.new_id,
							pho_id: pho_id
						}
					});
		};

		cardsNew.prototype.openUploader = function() {
					var uploader = new qq.FileUploaderBasic({
								button: $('.add_img')[0],
								action: "ajax/news.ajax.php",
								debug: true,
								name: "user_files",
								allowedExtensions: ["jpg", "jpeg", "png", "gif"],
								onSubmit: function() {
									var pos = $('.add_img').offset();
									waitingGif.open({ type: "small", left: (pos.left + $('.add_img').width() - 55), top: (pos.top) });

									uploader.setParams({
										action: "upload_pho",
										new_id: mycardsNew.new_id
									});
								},
								onComplete: function(id, fileName, responseJSON) {
									mycardsNew.addPho(responseJSON);
									waitingGif.close({type: "small"});
								}
							});

		};

		cardsNew.prototype.returnFromBiblio = function(pho_id) {
					mycardsNew.addPho(pho_id);
					$('#dialog_biblio').dialog("close");
		};

		cardsNew.prototype.imgList = function() {
					$('#new_edit')
							.undelegate('#photo_list .delete', "click")
							.delegate('#photo_list .delete', "click", function() {
								var pho_id = $(this).closest("li").data("pho_id");
								mycardsNew.removePho(pho_id);
							});
		};

		cardsNew.prototype.menuImglist = function() {
					$('#new_edit .menu_img .add_img')
							.unbind()
							.button({
								text: true,
								icons: {
									primary: "ui-icon-plusthick"
								}
							})
							.click(function() {
								mycardsNew.openUploader();
							});

					$('#new_edit .menu_img .open_biblio')
							.unbind()
							.button({
								text: true,
								icons: {
									primary: "ui-icon-image"
								}
							})
							.click(function() {
								var options = {
									new_id: mycardsNew.new_id,
									return_function: mycardsNew.returnFromBiblio,
									ajax_file: "ajax/news.ajax.php",
									ajax_file_action: "show_biblio",
									page_width: 822,
									fancybox: true,
									debug: true
								};
								var newbiblioPlugin = new biblioPlugin(options);
								newbiblioPlugin.init();
							});
		};

		cardsNew.prototype.actions = function() {
					$('#new_edit')
							.undelegate('.new_title input', "mouseenter mouseleave focusin focusout keyup")
							.delegate('.new_title input', "mouseenter mouseleave focusin focusout keyup", function(event) {
								switch(event.type)
								{
									case "mouseenter":
											$(this).addClass("ui-state-hover");
											break;

									case "mouseleave":
											$(this).removeClass("ui-state-hover");
											break;

									case "focusin":
											$(this).addClass("ui-state-active");
											break;

									case "focusout":
											$(this).removeClass("ui-state-active");
											break;

									case "keyup":
											$('.new[data-new_id="'+mycardsNew.new_id+'"] h2').html($(this).val());
											mycardsNew.save({new_title: $(this).val()});

											break;
								}
							});
		};

		cardsNew.prototype.init = function() {
					mycardsNew.cleditor();
					mycardsNew.actions();
					mycardsNew.date();
					mycardsNew.imgList();
					mycardsNew.menuImglist();
		};
		cardsNew.initialized = true;
	}
}



function newsList()
{
	mynewsList = this;

	if ( typeof newsList.initialized == "undefined" )
	{
		newsList.prototype.add = function() {
					$.post(
						"ajax/news.ajax.php",
						{
							action: "add_new"
						},
						function(data)
						{
							var tmp = data.split(":::");
							$('#liste_news').append(tmp[1]);
							mynewsList.actions();
							$('#new_id_'+tmp[0]).dblclick();
						}
					);
		};

		newsList.prototype.delete = function(new_id) {
					$('new_id_'+new_id).slideUp("fast", function() { $(this).remove(); });
					$.post(
						"ajax/news.ajax.php",
						{
							action: "delete_new",
							new_id: new_id
						}
					);
		};

		newsList.prototype.saveParution = function(new_id, new_show) {
					$.post(
						"ajax/news.ajax.php",
						{
							action: "save_show",
							new_id: new_id,
							new_show: new_show
						}
					);
		};

		newsList.prototype.actions = function() {

					$('#liste_news .button_delete')
						.unbind()
						.button({
							text: false,
							icons: {
								primary: "ui-icon-trash"
							}
						})
						.click(function() {
							var new_id = $(this).closest("div").data("new_id");
							if(confirm("Êtes vous sûr de supprimer cet new et donc les news qui en dépendent  ?"))
							{
								mynewsList.delete(new_id);
							}
						});

					$('#liste_news .new_show')
						.unbind()
						.click(function() {
							var new_show = $(this).attr("checked");
							var new_id = $(this).closest("div").data("new_id");
							mynewsList.saveParution(new_id, new_show);
						});

					$('#liste_news .new')
						.unbind()
						.dblclick(function() {
								if ($(this).hasClass("active"))
								{
									$('#liste_news .new')
										.removeClass("hidden")
										.removeClass("active");
									$('#new_edit')
										.empty();

									$('#new_add').removeClass("hidden");
									$('#new_edit').addClass("hidden");

								} else
								{
									var new_id = $(this).closest("div.new").data("new_id");

									$('#new_edit').load(
										"ajax/news.ajax.php",
										{
											action: "load_new",
											new_id : new_id,
										},
										function() {
											var cardNew = new cardsNew();
											cardNew.new_id = new_id;
											cardNew.init();
										}
									);
									$(this)
										.addClass("active")
										.siblings(".new").addClass("hidden");
									$('#new_edit').removeClass("hidden");
									$('#new_add').addClass("hidden");
								}
						});

					$('#new_add')
						.unbind()
						.button({
							text: true,
							icons: {
								primary: "ui-icon-plusthick"
							}
						})
						.click(function() {
								mynewsList.add();
						});
		};

		newsList.prototype.init = function() {
					mynewsList.actions();
		};
		newsList.initialized = true;
	}
}

jQuery(document).ready(function() {
	var newList = new newsList();
	newList.init();

	$('#dialog_biblio')
				.dialog("destroy")
				.dialog({
					autoOpen: false,
					height: "auto",
					width: "auto",
					modal: true,
					closeOnEscape: false,
					resizable: false,
					minHeight: 350
				});
});