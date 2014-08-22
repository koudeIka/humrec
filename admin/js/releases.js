function releasesList()
{
	myreleasesList = this;

	if ( typeof releasesList.initialized == "undefined" )
	{
		releasesList.prototype.add = function() {
					$.post(
						"ajax/releases.ajax.php",
						{
							action: "add_new"
						},
						function(data)
						{
							var tmp = data.split(":::");
							$('#liste_releases').append(tmp[1]);
							myreleasesList.actions();
							$('#rel_id_'+tmp[0]).dblclick();
						}
					);
		};

		releasesList.prototype.delete = function(rel_id) {
					$('rel_id_'+rel_id).slideUp("fast", function() { $(this).remove(); });
					$.post(
						"ajax/releases.ajax.php",
						{
							action: "delete_release",
							rel_id: rel_id
						}
					);
		};

		releasesList.prototype.saveParution = function(rel_id, rel_show) {
					$.post(
						"ajax/releases.ajax.php",
						{
							action: "save_show",
							rel_id: rel_id,
							rel_show: rel_show
						}
					);
		};

		releasesList.prototype.actions = function() {

					$('#liste_releases .button_delete')
						.unbind()
						.button({
							text: false,
							icons: {
								primary: "ui-icon-trash"
							}
						})
						.click(function() {
							var rel_id = $(this).closest("div").data("rel_id");
							if(confirm("Êtes vous sûr de supprimer cet release et donc les releases qui en dépendent  ?"))
							{
								myreleasesList.delete(rel_id);
							}
						});

					$('#liste_releases .rel_show')
						.unbind()
						.click(function() {
							var rel_show = $(this).attr("checked");
							var rel_id = $(this).closest("div").data("rel_id");
							myreleasesList.saveParution(rel_id, rel_show);
						});

					$('#liste_releases .release')
						.unbind()
						.dblclick(function() {
								if ($(this).hasClass("active"))
								{
									$('#liste_releases .release')
										.removeClass("hidden")
										.removeClass("active");
									$('#release_edit')
										.empty();

									$('#release_add').removeClass("hidden");
									$('#release_edit').addClass("hidden");

								} else
								{
									var rel_id = $(this).closest("div.release").data("rel_id");

									$('#release_edit').load(
										"ajax/releases.ajax.php",
										{
											action: "load_release",
											rel_id : rel_id,
										},
										function() {
											var cardRelease = new cardsRelease();
											cardRelease.rel_id = rel_id;
											cardRelease.init();
										}
									);
									$(this)
										.addClass("active")
										.siblings(".release").addClass("hidden");
									$('#release_edit').removeClass("hidden");
									$('#release_add').addClass("hidden");
								}
						});

					$('#release_add')
						.unbind()
						.button({
							text: true,
							icons: {
								primary: "ui-icon-plusthick"
							}
						})
						.click(function() {
								myreleasesList.add();
						});
		};

		releasesList.prototype.init = function() {
					myreleasesList.actions();
		};
		releasesList.initialized = true;
	}
}




function cardsRelease()
{
	mycardsRelease = this;
	mycardsRelease.rel_id = "";

	if ( typeof cardsRelease.initialized == "undefined" )
	{
			//sublab_id, art_id, rel_number, rel_title, rel_date, rel_content_fr, rel_content_en, rel_download, rel_paypal
		cardsRelease.prototype.save = function(parameters) {

					if ( typeof parameters.sublab_id != "undefined" )
					{
						$.post(
							"ajax/releases.ajax.php",
							{
									action: "save_sublab_id",
									rel_id: mycardsRelease.rel_id,
									sublab_id: parameters.sublab_id
							}
						);
					} else if ( typeof parameters.art_id != "undefined" )
					{
						$.post(
							"ajax/releases.ajax.php",
							{
									action: "save_art_id",
									rel_id: mycardsRelease.rel_id,
									art_id: parameters.art_id
							}
						);
					} else if ( typeof parameters.rel_number != "undefined" )
					{
						$.post(
							"ajax/releases.ajax.php",
							{
									action: "save_rel_number",
									rel_id: mycardsRelease.rel_id,
									rel_number: parameters.rel_number
							}
						);
					} else if ( typeof parameters.rel_url != "undefined" )
					{
						$.post(
							"ajax/releases.ajax.php",
							{
									action: "save_rel_url",
									rel_id: mycardsRelease.rel_id,
									rel_url: parameters.rel_url
							}
						);
					} else if ( typeof parameters.rel_title != "undefined" )
					{
						$.post(
							"ajax/releases.ajax.php",
							{
									action: "save_rel_title",
									rel_id: mycardsRelease.rel_id,
									rel_title: parameters.rel_title
							}
						);
					} else if ( typeof parameters.rel_date != "undefined" )
					{
						$.post(
								"ajax/releases.ajax.php",
								{
										action: "save_rel_date",
										rel_id: mycardsRelease.rel_id,
										rel_date: parameters.rel_date
								}
						);
					} else if ( typeof parameters.rel_content_fr != "undefined" )
					{
						$.post(
								"ajax/releases.ajax.php",
								{
									action: "save_rel_content",
									rel_id: mycardsRelease.rel_id,
									rel_content_fr: parameters.rel_content_fr
								}
							);
					} else if ( typeof parameters.rel_content_en != "undefined" )
					{
						$.post(
							"ajax/releases.ajax.php",
							{
								action: "save_rel_content",
								rel_id: mycardsRelease.rel_id,
								rel_content_en: parameters.rel_content_en
							}
						);
					} else if ( typeof parameters.rel_download != "undefined" )
					{
						$.post(
							"ajax/releases.ajax.php",
							{
									action: "save_rel_download",
									rel_id: mycardsRelease.rel_id,
									rel_download: parameters.rel_download
							}
						);
					} else if (typeof parameters.tra_id != "undefined")
					{
							$.post(
								"ajax/releases.ajax.php",
								{
										action: "save_track",
										rel_id: mycardsRelease.rel_id,
										tra_id: parameters.tra_id,
										tra_digit: parameters.tra_digit,
										tra_name: parameters.tra_name,
										tra_url: parameters.tra_url,
										tra_order: parameters.tra_order
								}
							);
					} else if (typeof parameters.rel_paypal_id != "undefined")
					{
							$.post(
								"ajax/releases.ajax.php",
								{
										action: "save_paypal",
										rel_id: mycardsRelease.rel_id,
										rel_paypal_id: parameters.rel_paypal_id
								}
							);
					} else if (typeof parameters.rel_paypal_priceue != "undefined")
					{
							$.post(
								"ajax/releases.ajax.php",
								{
										action: "save_paypal",
										rel_id: mycardsRelease.rel_id,
										rel_paypal_priceue: parameters.rel_paypal_priceue
								}
							);
					} else if (typeof parameters.rel_paypal_priceworld != "undefined")
					{
							$.post(
								"ajax/releases.ajax.php",
								{
										action: "save_paypal",
										rel_id: mycardsRelease.rel_id,
										rel_paypal_priceworld: parameters.rel_paypal_priceworld
								}
							);
					} else if (typeof parameters.rel_paypal_currency_code != "undefined")
					{
							$.post(
								"ajax/releases.ajax.php",
								{
										action: "save_paypal",
										rel_id: mycardsRelease.rel_id,
										rel_paypal_currency_code: parameters.rel_paypal_currency_code
								}
							);
					} else if (typeof parameters.rel_paypal_encrypted != "undefined")
					{
							$.post(
								"ajax/releases.ajax.php",
								{
										action: "save_paypal",
										rel_id: mycardsRelease.rel_id,
										rel_paypal_encrypted: parameters.rel_paypal_encrypted
								}
							);
					}
		};

		cardsRelease.prototype.tracklist = function() {
				$('.rel_tracklist table tbody')
						.undelegate('input, textarea', "mouseenter mouseleave focusin focusout keyup")
						.delegate('input, textarea', "mouseenter mouseleave focusin focusout keyup", function(event) {
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
										var tra_id = $(this).closest("tr").data("tra_id");
										var tra_digit = $(this).closest("tr").find(".tra_digit input").val();
										var tra_name = $(this).closest("tr").find(".tra_name textarea").val();
										var tra_url = $(this).closest("tr").find(".tra_url input").val();
										var tra_order = $(this).closest("tr").data("tra_order");
										
										mycardsRelease.save({
												tra_id: tra_id,
												tra_digit: tra_digit,
												tra_name: tra_name,
												tra_url: tra_url,
												tra_order: tra_order
											});
										break;
							}

						})
						.undelegate('.delete', "click")
						.delegate('.delete', "click", function(event) {
								var tra_id = $(this).closest("tr").data("tra_id");
								mycardsRelease.removeTrack({ tra_id: tra_id });
						});
		};

		cardsRelease.prototype.removeTrack = function(parameters) {
					$('.rel_tracklist table tr[data-tra_id="'+parameters.tra_id+'"]').slideUp("fast", function() { $(this).remove(); });
					$.post(
						"ajax/releases.ajax.php",
						{
							action: "del_track",
							tra_id: parameters.tra_id
						}
					);
		};

		cardsRelease.prototype.addTrack = function() {
					$.post(
						"ajax/releases.ajax.php",
						{
								action: "add_track",
								rel_id: mycardsRelease.rel_id
						}, function(data) {
							$('.rel_tracklist table tbody').append(data);
						}
					);
		};

		cardsRelease.prototype.menuTracklist = function() {
					$('.menu_tab .add_row')
							.unbind()
							.button({
								text: true,
								icons: {
									primary: "ui-icon-plusthick"
								}
							})
							.click(function() {
								mycardsRelease.addTrack();
							});
		};

		cardsRelease.prototype.date = function() {
					$('#release_edit .rel_date input').datepicker({
							dateFormat: "dd/mm/yy",
							onSelect: function(dateText, inst) {
									mycardsRelease.save({rel_date: dateText});
							}
						});
		};

		cardsRelease.prototype.cleditor = function() {
					$('#release_edit .rel_content_fr textarea')
						.cleditor({
							width:        660, // width not including margins, borders or padding
							height:       250, // height not including margins, borders or padding
							controls:     // controls to add to the toolbar
								"bold italic underline strikethrough subscript superscript | font size " +
								" | color highlight removeformat | bullets numbering | outdent " +
								"indent | alignleft center alignright justify | " +
								"rule image link unlink | source",
						})
						.change(function() {
							var editor = $('#release_edit .rel_content_fr textarea').cleditor()[0];
							editor.updateTextArea();
							mycardsRelease.save({rel_content_fr: $('#release_edit .rel_content_fr textarea').val()});
						});
					$('#release_edit .rel_content_fr textarea').cleditor()[0].refresh();

					$('#release_edit .rel_content_en textarea')
						.cleditor({
							width:        660, // width not including margins, borders or padding
							height:       250, // height not including margins, borders or padding
							controls:     // controls to add to the toolbar
								"bold italic underline strikethrough subscript superscript | font size " +
								" | color highlight removeformat | bullets numbering | outdent " +
								"indent | alignleft center alignright justify | " +
								"rule image link unlink | source",
						})
						.change(function() {
							var editor = $('#release_edit .rel_content_en textarea').cleditor()[0];
							editor.updateTextArea();
							mycardsRelease.save({rel_content_en: $('#release_edit .rel_content_en textarea').val()});
						});
					$('#release_edit .rel_content_en textarea').cleditor()[0].refresh();
		};

		cardsRelease.prototype.addPho = function(pho_id) {
					$.ajax({
						type: "POST",
						url	: 	"ajax/releases.ajax.php",
					        data : {
							action: "add_pho",
							rel_id: mycardsRelease.rel_id,
							pho_id: pho_id
						},
						success	: 	function(data){
							if ($('#photo_list').length == 0)
							{
							    $('.rel_pictures .menu_img').before("<ul id='photo_list'></ul>");
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

		cardsRelease.prototype.removePho = function(pho_id) {
					$('#photo_list li[data-pho_id="'+pho_id+'"]').slideUp("fast", function() { $(this).remove(); });
					$.ajax({
						type: "POST",
						url	: 	"ajax/releases.ajax.php",
					        data : {
							action: "del_pho",
							rel_id: mycardsRelease.rel_id,
							pho_id: pho_id
						}
					});
		};

		cardsRelease.prototype.openUploader = function() {
					var uploader = new qq.FileUploaderBasic({
								button: $('.add_img')[0],
								action: "ajax/releases.ajax.php",
								debug: true,
								name: "user_files",
								allowedExtensions: ["jpg", "jpeg", "png", "gif"],
								onSubmit: function() {
									var pos = $('.add_img').offset();
									waitingGif.open({ type: "small", left: (pos.left + $('.add_img').width() - 55), top: (pos.top) });

									uploader.setParams({
										action: "upload_pho",
										rel_id: mycardsRelease.rel_id
									});
								},
								onComplete: function(id, fileName, responseJSON) {
									mycardsRelease.addPho(responseJSON);
									waitingGif.close({type: "small"});
								}
							});

		};

		cardsRelease.prototype.returnFromBiblio = function(pho_id) {
					mycardsRelease.addPho(pho_id);
					$('#dialog_biblio').dialog("close");
		};

		cardsRelease.prototype.imgList = function() {
					$('#release_edit')
							.undelegate('#photo_list .delete', "click")
							.delegate('#photo_list .delete', "click", function() {
								var pho_id = $(this).closest("li").data("pho_id");
								mycardsRelease.removePho(pho_id);
							});
		};

		cardsRelease.prototype.menuImglist = function() {
					$('#release_edit .menu_img .add_img')
							.unbind()
							.button({
								text: true,
								icons: {
									primary: "ui-icon-plusthick"
								}
							})
							.click(function() {
								mycardsRelease.openUploader();
							});

					$('#release_edit .menu_img .open_biblio')
							.unbind()
							.button({
								text: true,
								icons: {
									primary: "ui-icon-image"
								}
							})
							.click(function() {
								var options = {
									rel_id: mycardsRelease.rel_id,
									return_function: mycardsRelease.returnFromBiblio,
									ajax_file: "ajax/releases.ajax.php",
									ajax_file_action: "show_biblio",
									page_width: 822,
									fancybox: true,
									debug: true
								};
								var newbiblioPlugin = new biblioPlugin(options);
								newbiblioPlugin.init();
							});
		};

		cardsRelease.prototype.actions = function() {
					$('#release_edit')
							.undelegate('.sublab_id select', "change")
							.delegate('.sublab_id select', "change", function() {
									mycardsRelease.save({sublab_id: $(this).val()});
							})
							.undelegate('.art_id select', "change")
							.delegate('.art_id select', "change", function() {
									$('.release[data-rel_id="'+mycardsRelease.rel_id+'"] h2').html($('#release_edit .rel_number input').val()+" | "+$('#release_edit .art_id select :selected').text());
									mycardsRelease.save({art_id: $(this).val()});
							})
							.undelegate('.rel_number input', "mouseenter mouseleave focusin focusout keyup")
							.delegate('.rel_number input', "mouseenter mouseleave focusin focusout keyup", function(event) {
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
											$('.release[data-rel_id="'+mycardsRelease.rel_id+'"] h2').html($('#release_edit .rel_number input').val()+" | "+$('#release_edit .art_id select :selected').text());
											mycardsRelease.save({rel_number: $(this).val()});
											break;
								}
							})
							.undelegate('.rel_title input', "mouseenter mouseleave focusin focusout keyup")
							.delegate('.rel_title input', "mouseenter mouseleave focusin focusout keyup", function(event) {
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
											mycardsRelease.save({rel_title: $(this).val()});
											break;
								}
							})
							.undelegate('.rel_url input', "mouseenter mouseleave focusin focusout keyup")
							.delegate('.rel_url input', "mouseenter mouseleave focusin focusout keyup", function(event) {
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
											mycardsRelease.save({rel_url: $(this).val()});
											break;
								}
							})
							.undelegate('.rel_download input', "mouseenter mouseleave focusin focusout keyup")
							.delegate('.rel_download input', "mouseenter mouseleave focusin focusout keyup", function(event) {
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
											mycardsRelease.save({rel_download: $(this).val()});
											break;
								}
							})
							.undelegate('.rel_paypal input', "mouseenter mouseleave focusin focusout keyup")
							.delegate('.rel_paypal input', "mouseenter mouseleave focusin focusout keyup", function(event) {
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
											switch($(this).attr("name"))
											{
												case "rel_paypal_id":
													mycardsRelease.save({rel_paypal_id: $(this).val()});
													break;

												case "rel_paypal_priceue":
													mycardsRelease.save({rel_paypal_priceue: $(this).val()});
													break;

												case "rel_paypal_priceworld":
													mycardsRelease.save({rel_paypal_priceworld: $(this).val()});
													break;

												case "rel_paypal_currency_code":
													mycardsRelease.save({rel_paypal_currency_code: $(this).val()});
													break;

												case "rel_paypal_encrypted":
													mycardsRelease.save({rel_paypal_encrypted: $(this).val()});
													break;
											}
											break;
								}
							});
		};

		cardsRelease.prototype.init = function() {
					mycardsRelease.date();
					mycardsRelease.cleditor();
					mycardsRelease.menuTracklist();
					mycardsRelease.tracklist();
					mycardsRelease.actions();
					mycardsRelease.imgList();
					mycardsRelease.menuImglist();
		};
		cardsRelease.initialized = true;
	}
}





jQuery(document).ready(function() {
	var releaseList = new releasesList();
	releaseList.init();

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