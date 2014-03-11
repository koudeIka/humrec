function paypalForm()
{
      $('.release-buy a').click(function() {
	    var os0 = $(this).data("region");
	    $(this)
		  .closest('.release').find('.release-options input[name="os0"]').val(os0)
		  .closest('.paypalForm').submit();
	    return false;

      });
}


var audio_tabs = new Array;


// function iniPlaylist(parameters)
// {
// 	audio_tabs[parameters.rel_id] = audiojs.create(this, {
// 					trackEnded: function() {
// 						var next = $('.release[data-rel_id="'+parameters.rel_id+'"] a.playing').parent().next();
// 						if (!next.length) next = $('.release[data-rel_id="'+parameters.rel_id+'"] .tracklist li').first();
// 						var track_id = next.data("track_id");
// 						listenThis({ track_id: track_id, rel_id: parameters.rel_id });
// 					}
// 				});
// }

function listenThis(parameters)
{
	
	var src = $('.track-details[data-track_id="'+parameters.track_id+'"] a').data("src");
	
	// ajout de la classe
	$('.release[data-rel_id="'+parameters.rel_id+'"]').find("a.playing").removeClass("playing");
	$('.track-details[data-track_id="'+parameters.track_id+'"] a').addClass("playing");
	
	// on rempli la balise audio avec la bonne source
	$('.release[data-rel_id="'+parameters.rel_id+'"]').find("audio").attr("src", src);
	
	// aller go
	audio_tabs[parameters.rel_id].load(src);
	audio_tabs[parameters.rel_id].play();
	return false;
}


jQuery(document).ready(function() {
		paypalForm();
		
		$('audio').each(function() {
			var rel_id = $(this).closest('.release').data("rel_id");
			
			//iniPlaylist({ rel_id: rel_id });
			audio_tabs[rel_id] = audiojs.create(this, {
					trackEnded: function() {
						var next = $('.release[data-rel_id="'+rel_id+'"] a.playing').parent().next();
						if (!next.length) next = $('.release[data-rel_id="'+rel_id+'"] .tracklist li').first();
						var track_id = next.data("track_id");
						listenThis({ track_id: track_id, rel_id: rel_id });
					}
				});
		});
		
		$('.tracklist')
			.off("click mouseenter mouseleave", '.track-details a')
			.on("click mouseenter mouseleave", '.track-details a', function(event) {
				event.preventDefault();
				var rel_id = $(this).closest('.release').data("rel_id");
				var track_id = $(this).closest('.track-details').data("track_id");
				switch(event.type)
				{
					case "click":
								listenThis({ track_id: track_id, rel_id: rel_id });
								break;
							
					case "mouseenter":
								break;
				
					case "mouseleave":
								break;
				}
			});
    
    

});