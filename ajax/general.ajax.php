<?php
require_once('../application_top.php');


switch($_POST['action'])
{
      case "newsletter_subscribe":
				    $to      = "contact@humrec.org";
				    $subject = '[humrec.org] Inscription Newsletter';
				    $message = "Salut ! \r\n Wunderbar ! quelqu'un s'est inscrit, voici l'adresse : ".$_POST['mail'];
				    $headers = 'From: contact@humrec.org' . "\r\n" .
				    'Reply-To: contact@humrec.org' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
				    mail($to, $subject, $message, $headers);

				    $to      = $_POST['mail'];
				    $subject = '[humrec.org] Bienvenue / Welcome';
				     if($_SESSION['lang'] == "fr")
				     {
					    $message = "Vous venez de joindre la mailing liste d'Humeur";
				     }
				     else
				     {
					     $message = "Welcome to the newsletter of humus records";
				     }
				    $headers = 'From: contact@humrec.org' . "\r\n" .
				    'Reply-To: contact@humrec.org' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
				    mail($to, $subject, $message, $headers);

				    
				    if($_SESSION['lang'] == "fr")
				    {
					echo "Merci pour votre inscription !";
				    } else
				    {
					echo "Thanks for your subscription !";
				    }
				    $newsletter = new newsletters();
				    $newsletter->new_mail = $_POST['mail'];
				    $newsletter->save();
				    

				    break;
}

?> 