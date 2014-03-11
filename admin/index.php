<?php
require_once('application_top.php');
include_once('header.php');


if(!empty($_SESSION['connected']))
{
	echo "<p>ok that's work</p>";
} else
{
	echo "	<form id='form' method='post' action='".$_SERVER["PHP_SELF"]."'>
				<div id='login'>
					<ul>
						<li>
							<label>Identifiant : </label>
							<input type='text' name='usr_login'  value='' />
						</li>
						<li>
							<label>Mot de passe : </label>
							<input type='password' name='usr_passwd' value='' />
						</li>
						<li>
							<input type='hidden' name='connexion' value='ok' />
							<input type='submit' id='logon' name='logon' class='button' value=\"s'identifier\" />
						</li>
					</ul>";
		if(!empty($tentative_login)) { echo "<div id='message'><span id='clip'>&nbsp;</span><span id='close'>⊗</span>".$tentative_login."</div>"; };
		echo " 	</div>
			</form>";
}
include_once('footer.php');
?>