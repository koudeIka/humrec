function artistesList()
{
	myartistesList = this;

	if ( typeof artistesList.initialized == "undefined" )
	{
		artistesList.prototype.add = function() {
					$.post(
						"ajax/artistes.ajax.php",
						{
							action: "add_new"
						},
						function(data)
						{
							var tmp = data.split(":::");
							$('#liste_artistes').append(tmp[1]);
							myartistesList.actions();
							$('#art_id_'+tmp[0]).dblclick();
						}
					);
		};

		artistesList.prototype.delete = function(art_id) {
					$('art_id_'+art_id).slideUp("fast", function() { $(this).remove(); });
					$.post(
						"ajax/artistes.ajax.php",
						{
							action: "delete_artiste",
							art_id: art_id
						}
					);
		};

		artistesList.prototype.saveParution = function(art_id, art_show) {
					$.post(
						"ajax/artistes.ajax.php",
						{
							action: "save_show",
							art_id: art_id,
							art_show: art_show
						}
					);
		};

		artistesList.prototype.actions = function() {

					$('#liste_artistes .button_delete')
						.unbind()
						.button({
							text: false,
							icons: {
								primary: "ui-icon-trash"
							}
						})
						.click(function() {
							var art_id = $(this).closest("div").data("art_id");
							if(confirm("Êtes vous sûr de supprimer cet artiste et donc les artistes qui en dépendent  ?"))
							{
								myartistesList.delete(art_id);
							}
						});

					$('#liste_artistes .art_show')
						.unbind()
						.click(function() {
							var art_show = $(this).attr("checked");
							var art_id = $(this).closest("div").data("art_id");
							myartistesList.saveParution(art_id, art_show);
						});

					$('#liste_artistes .artiste')
						.unbind()
						.dblclick(function() {
								if ($(this).hasClass("active"))
								{
									$('#liste_artistes .artiste')
										.removeClass("hidden")
										.removeClass("active");
									$('#artiste_edit')
										.empty();

									$('#artiste_add').removeClass("hidden");
									$('#artiste_edit').addClass("hidden");

								} else
								{
									var art_id = $(this).closest("div.artiste").data("art_id");

									$('#artiste_edit').load(
										"ajax/artistes.ajax.php",
										{
											action: "load_artiste",
											art_id : art_id,
										},
										function() {
											var cardArtiste = new cardsArtiste();
											cardArtiste.art_id = art_id;
											cardArtiste.init();
										}
									);
									$(this)
										.addClass("active")
										.siblings(".artiste").addClass("hidden");
									$('#artiste_edit').removeClass("hidden");
									$('#artiste_add').addClass("hidden");
								}
						});

					$('#artiste_add')
						.unbind()
						.button({
							text: true,
							icons: {
								primary: "ui-icon-plusthick"
							}
						})
						.click(function() {
								myartistesList.add();
						});
		};

		artistesList.prototype.init = function() {
					myartistesList.actions();
		};
		artistesList.initialized = true;
	}
}




function cardsArtiste()
{
	mycardsArtiste = this;
	mycardsArtiste.art_id = "";

	if ( typeof cardsArtiste.initialized == "undefined" )
	{
			//sublab_id, art_id, rel_number, rel_title, rel_date, art_description_fr, art_description_en, rel_download, rel_paypal
		cardsArtiste.prototype.save = function(parameters) {

					if ( typeof parameters.art_name != "undefined" )
					{
						$.post(
							"ajax/artistes.ajax.php",
							{
									action: "save_art_name",
									art_id: mycardsArtiste.art_id,
									art_name: parameters.art_name
							}
						);
					} else if ( typeof parameters.art_url != "undefined" )
					{
						$.post(
							"ajax/artistes.ajax.php",
							{
									action: "save_art_url",
									art_id: mycardsArtiste.art_id,
									art_url: parameters.art_url
							}
						);
					} else if ( typeof parameters.art_homepage != "undefined" )
					{
						$.post(
							"ajax/artistes.ajax.php",
							{
									action: "save_art_homepage",
									art_id: mycardsArtiste.art_id,
									art_homepage: parameters.art_homepage
							}
						);
					} else if ( typeof parameters.art_description_fr != "undefined" )
					{
						$.post(
								"ajax/artistes.ajax.php",
								{
									action: "save_art_description",
									art_id: mycardsArtiste.art_id,
									art_description_fr: parameters.art_description_fr
								}
							);
					} else if ( typeof parameters.art_description_en != "undefined" )
					{
						$.post(
							"ajax/artistes.ajax.php",
							{
								action: "save_art_description",
								art_id: mycardsArtiste.art_id,
								art_description_en: parameters.art_description_en
							}
						);
					}
		};

		cardsArtiste.prototype.cleditor = function() {
					$('#artiste_edit .art_description_fr textarea')
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
							var editor = $('#artiste_edit .art_description_fr textarea').cleditor()[0];
							editor.updateTextArea();
							mycardsArtiste.save({art_description_fr: $('#artiste_edit .art_description_fr textarea').val()});
						});
					$('#artiste_edit .art_description_fr textarea').cleditor()[0].refresh();

					$('#artiste_edit .art_description_en textarea')
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
							var editor = $('#artiste_edit .art_description_en textarea').cleditor()[0];
							editor.updateTextArea();
							mycardsArtiste.save({art_description_en: $('#artiste_edit .art_description_en textarea').val()});
						});
					$('#artiste_edit .art_description_en textarea').cleditor()[0].refresh();
		};

		cardsArtiste.prototype.addPho = function(pho_id) {
					$.ajax({
						type: "POST",
						url	: 	"ajax/artistes.ajax.php",
					        data : {
							action: "add_pho",
							art_id: mycardsArtiste.art_id,
							pho_id: pho_id
						},
						success	: 	function(data){
							if ($('#photo_list').length == 0)
							{
							    $('.art_pictures .menu_img').before("<ul id='photo_list'></ul>");
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

		cardsArtiste.prototype.removePho = function(pho_id) {
					$('#photo_list li[data-pho_id="'+pho_id+'"]').slideUp("fast", function() { $(this).remove(); });
					$.ajax({
						type: "POST",
						url	: 	"ajax/artistes.ajax.php",
					        data : {
							action: "del_pho",
							art_id: mycardsArtiste.art_id,
							pho_id: pho_id
						}
					});
		};

		cardsArtiste.prototype.openUploader = function() {
					var uploader = new qq.FileUploaderBasic({
								button: $('.add_img')[0],
								action: "ajax/artistes.ajax.php",
								debug: true,
								name: "user_files",
								allowedExtensions: ["jpg", "jpeg", "png", "gif"],
								onSubmit: function() {
									var pos = $('.add_img').offset();
									waitingGif.open({ type: "small", left: (pos.left + $('.add_img').width() - 55), top: (pos.top) });

									uploader.setParams({
										action: "upload_pho",
										art_id: mycardsArtiste.art_id
									});
								},
								onComplete: function(id, fileName, responseJSON) {
									mycardsArtiste.addPho(responseJSON);
									waitingGif.close({type: "small"});
								}
							});

		};

		cardsArtiste.prototype.returnFromBiblio = function(pho_id) {
					mycardsArtiste.addPho(pho_id);
					$('#dialog_biblio').dialog("close");
		};

		cardsArtiste.prototype.imgList = function() {
					$('#artiste_edit')
							.undelegate('#photo_list .delete', "click")
							.delegate('#photo_list .delete', "click", function() {
								var pho_id = $(this).closest("li").data("pho_id");
								mycardsArtiste.removePho(pho_id);
							});
		};

		cardsArtiste.prototype.menuImglist = function() {
					$('#artiste_edit .menu_img .add_img')
							.unbind()
							.button({
								text: true,
								icons: {
									primary: "ui-icon-plusthick"
								}
							})
							.click(function() {
								mycardsArtiste.openUploader();
							});

					$('#artiste_edit .menu_img .open_biblio')
							.unbind()
							.button({
								text: true,
								icons: {
									primary: "ui-icon-image"
								}
							})
							.click(function() {
								var options = {
									art_id: mycardsArtiste.art_id,
									return_function: mycardsArtiste.returnFromBiblio,
									ajax_file: "ajax/artistes.ajax.php",
									ajax_file_action: "show_biblio",
									page_width: 822,
									fancybox: true,
									debug: true
								};
								var newbiblioPlugin = new biblioPlugin(options);
								newbiblioPlugin.init();
							});
		};

		cardsArtiste.prototype.actions = function() {
					$('#artiste_edit')
							.undelegate('.art_name input', "mouseenter mouseleave focusin focusout keyup")
							.delegate('.art_name input', "mouseenter mouseleave focusin focusout keyup", function(event) {
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
											$('.artiste[data-art_id="'+mycardsArtiste.art_id+'"] h2').html($(this).val());
											mycardsArtiste.save({art_name: $(this).val()});
											break;
								}
							})
							.undelegate('.art_url input', "mouseenter mouseleave focusin focusout keyup")
							.delegate('.art_url input', "mouseenter mouseleave focusin focusout keyup", function(event) {
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
											mycardsArtiste.save({art_url: $(this).val()});
											break;
								}
							})
							.undelegate('.art_homepage input', "mouseenter mouseleave focusin focusout keyup")
							.delegate('.art_homepage input', "mouseenter mouseleave focusin focusout keyup", function(event) {
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
											mycardsArtiste.save({art_homepage: $(this).val()});
											break;
								}
							});
		};

		cardsArtiste.prototype.init = function() {
					mycardsArtiste.cleditor();
					mycardsArtiste.actions();
					mycardsArtiste.imgList();
					mycardsArtiste.menuImglist();
		};
		cardsArtiste.initialized = true;
	}
}




jQuery(document).ready(function() {
	var artisteList = new artistesList();
	artisteList.init();

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

