function dispayError(msg) { alert(msg); }

function validateEmail(email) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if( !emailReg.test( email ) ) {
		return false;
	} else {
		return true;
	}
}


jQuery(document).ready(function() {
	
	$('#navigation')
		.on("click", '[href="releases.php"]', function() {
			if($('#releases_link').is(":visible"))
			{
				$('#releases_link').slideUp();
			}
			else
			{
				$('#releases_link').slideDown();
			}
			return false;
		})
		.on("click", '[href="artistes.php"]', function() {
			if($('#artistes_link').is(":visible"))
			{
				$('#artistes_link').slideUp();
			}
			else
			{
				$('#artistes_link').slideDown();
			}
			return false;
		});

	$('#newsletter_mail')
		    .focus(function() {
			  $(this).addClass("focus");
		    })
		    .blur(function() {
			   $(this).removeClass("focus");
		    })
		    .keypress(function(event) {

		      if (event.which == 13) {
			if (validateEmail($(this).val()) === true)
			{

			  $.ajax({
				  type: "POST",
				  url	: 	"ajax/general.ajax.php",
				  data	: {
					    action: "newsletter_subscribe",
					    mail: $(this).val()
				  },
				  success: 	function(data)
						{
							$('#newsletter').hide().html(data).fadeIn("slow");
						},
				  error	: 	function(msg){ dispayError(msg); }
			  });
			 } else
			 {
			    $('#newsletter').append("<p class='news_alert'>This not a mail address !</p>").find("p").fadeOut(5000);
			 }
		      }
		    })

	$('#contenu a.photo').fancybox({
		openEffect	: "elastic",
		closeEffect	: "elastic"
	});

	 $('#entete').jqFancyTransitions({
			effect: "curtain",
			width: 1000,
			height: 300,
			strips: 30,
			delay: 15000,
			stripDelay: 500,
			position: "curtain",
			direction: "fountain"
	 });
});