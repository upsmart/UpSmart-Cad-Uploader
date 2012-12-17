<?php


/* ************************************************************************* *\
	MAILING
\* ************************************************************************* */

// TODO correct i18n, custom blog admin messages

// BLOG_NAME, USER_NAME, EMAIL, ADMIN_EMAIL, SITE_NAME, USER_EMAIL, SUPERADMIN_EMAIL, SITE_URL, PASSWORD, LOGIN_URL, BLOG_URL

/*

define( 'MURM_MAIL_DENY_FROM_ADMIN_SUBJECT', '[%BLOG_NAME%] Zamítnutí žádosti o registraci' );
define( 'MURM_MAIL_DENY_FROM_ADMIN_MESSAGE', 'Litujeme, ale vaše žádost o registraci na blog %BLOG_NAME% pod uživatelským jménem %USER_NAME% byla zamítnuta vlastníkem tohoto blogu. Pro vysvětlení jej můžete kontaktovat na adrese %ADMIN_EMAIL%.

%SITE_NAME% (MURM)' );


define( 'MURM_MAIL_ADMIN_NEW_REQUEST_SUBJECT', '[%BLOG_NAME%] Nová žádost o registraci' );
define( 'MURM_MAIL_ADMIN_NEW_REQUEST_MESSAGE', 'Na vašem blogu je nová žádost o registraci uživatele %USER_NAME% (%USER_EMAIL%). Pro zodpovězení žádosti pokračujte, prosím, na %BLOG_URL%/wp-admin/users.php?page=murm-moderation.

%SITE_NAME% (MURM)' );

define( 'MURM_MAIL_SUPERADMIN_NEW_REQUEST_SUBJECT', '[%BLOG_NAME%] Nová žádost o registraci' );
define( 'MURM_MAIL_SUPERADMIN_NEW_REQUEST_MESSAGE', 'Vlastník blogu %BLOG_NAME% žádá o potvrzení registrace uživatele %USER_NAME% (%USER_EMAIL%) na svém blogu. Pro zodpovězení žádosti pokračujte, prosím, na %SITE_URL%/wp-admin/network/users.php?page=murm-superadmin.

%SITE_NAME% (MURM)' );

define( 'MURM_MAIL_DENY_FROM_SUPERADMIN_SUBJECT', '[%BLOG_NAME%] Zamítnutí žádosti o registraci' );
define( 'MURM_MAIL_DENY_FROM_SUPERADMIN_MESSAGE', 'Litujeme, ale vaše žádost o registraci na blog %BLOG_NAME% pod uživatelským jménem %USER_NAME% byla zamítnuta správcem sítě. Pro vysvětlení jej můžete kontaktovat na adrese %SUPERADMIN_EMAIL%.

%SITE_NAME% (MURM)' );


define( 'MURM_MAIL_SUPERADMIN_TO_ADMIN_DENY_SUBJECT', '[%BLOG_NAME%] Zamítnutí žádosti o registraci uživatele %USER_NAME%' );
define( 'MURM_MAIL_SUPERADMIN_TO_ADMIN_DENY_MESSAGE', 'Litujeme, ale vaše žádost o registraci uživatele %USER_NAME% na váš blog %BLOG_NAME% byla zamítnuta správcem sítě. Pro vysvětlení jej můžete kontaktovat na adrese %SUPERADMIN_EMAIL%. Žádajícímu byl odeslán e-mail s oznámením.

%SITE_NAME% (MURM)' );


define( 'MURM_MAIL_SUPERADMIN_TO_ADMIN_APPROVE_SUBJECT', '[%BLOG_NAME%] Povolení žádosti o registraci uživatele %USER_NAME%' );
define( 'MURM_MAIL_SUPERADMIN_TO_ADMIN_APPROVE_MESSAGE', 'Správce sítě potvrdil vaši žádost o registraci uživatele %USER_NAME% na vašen blogu %BLOG_NAME%. Uživateli byl poslán e-mail s přihlašovacími údaji. Pokud se vyskytnou problémy, kontaktujte správce na %SUPERADMIN_EMAIL%.

%SITE_NAME% (MURM)' );


define( 'MURM_MAIL_APPROVE_NEW_SUBJECT', '[%BLOG_NAME%] Registrace uživatelského účtu %USER_NAME%' );
define( 'MURM_MAIL_APPROVE_NEW_MESSAGE', 'Vaše registrace na blogu %BLOG_NAME% byla potvrzena. Níže naleznete údaje potřebné pro přihlášení. 

Důležité informace
(1) Tyto údaje jsou určeny výhradně vám osobně, s nikým je nesdílejte! 
(2) Budete-li v budoucnu žádat o registraci na jiném blogu v síti %SITE_NAME%, použijte přesně stejnou kombinaci uživatelského jména a e-mailu (%USER_NAME%, %USER_EMAIL%).

Pokud se vyskytnou nějaké problémy nebo nejasnosti, bez obav kontaktujte správce sítě na adrese %SUPERADMIN_EMAIL%.

Uživatelské jméno: %USER_NAME%
E-mail: %USER_EMAIL%
Heslo: %PASSWORD%

Přihlásit se můžete na adrese %LOGIN_URL%.

Vítejte!
%SITE_NAME% (MURM)' );

define( 'MURM_MAIL_APPROVE_EXISTING_SUBJECT', '[%BLOG_NAME%] Registrace uživatelského účtu %USER_NAME%' );
define( 'MURM_MAIL_APPROVE_EXISTING_MESSAGE', 'Vaše registrace na blogu %BLOG_NAME% byla potvrzena. Pro přihlášení použijte údaje, které vám k tomuto uživatelskému jménu v síti %SITE_NAME% byly zaslány dříve.

Důležité: budete-li v budoucnu žádat o registraci na jiném blogu v síti %SITE_NAME%, použijte přesně stejnou kombinaci uživatelského jména a e-mailu (%USER_NAME%, %USER_EMAIL%).

Pokud se vyskytnou nějaké problémy nebo nejasnosti, bez obav kontaktujte správce sítě na adrese %SUPERADMIN_EMAIL%.

Přihlásit se můžete na adrese %LOGIN_URL%.

Vítejte!
%SITE_NAME% (MURM)' );


*/


define( 'MURM_MAIL_DENY_FROM_ADMIN_SUBJECT', '[%BLOG_NAME%] Registration request denial' );
define( 'MURM_MAIL_DENY_FROM_ADMIN_MESSAGE', "We are sorry, but your request for registration on blog %BLOG_NAME% with user name %USER_NAME% was denied by this blog's owner. For more information you can contact them via %ADMIN_EMAIL%.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager" );


define( 'MURM_MAIL_ADMIN_NEW_REQUEST_SUBJECT', '[%BLOG_NAME%] New registration request' );
define( 'MURM_MAIL_ADMIN_NEW_REQUEST_MESSAGE', "There is a new registration request from %USER_NAME% (%USER_EMAIL%) on your blog. For processing it please go to %BLOG_URL%/wp-admin/users.php?page=murm-moderation.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager" );


define( 'MURM_MAIL_SUPERADMIN_NEW_REQUEST_SUBJECT', '[%BLOG_NAME%] New registration request' );
define( 'MURM_MAIL_SUPERADMIN_NEW_REQUEST_MESSAGE', "The admin of %BLOG_NAME% requests registration of user %USER_NAME% (%USER_EMAIL%) on it's blog. For processing it please go to %SITE_URL%/wp-admin/network/users.php?page=murm-superadmin.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager" );


define( 'MURM_MAIL_DENY_FROM_SUPERADMIN_SUBJECT', '[%BLOG_NAME%] Registration request denial' );
define( 'MURM_MAIL_DENY_FROM_SUPERADMIN_MESSAGE', "We are sorry, but your request for registration on blog %BLOG_NAME% with user name %USER_NAME% was denied by network administrator. For more information you can contact them via %SUPERADMIN_EMAIL%.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager" );


define( 'MURM_MAIL_SUPERADMIN_TO_ADMIN_DENY_SUBJECT', '[%BLOG_NAME%] Registration request from user %USER_NAME% denied' );
define( 'MURM_MAIL_SUPERADMIN_TO_ADMIN_DENY_MESSAGE', "We are sorry, but your registration request for user %USER_NAME% on your blog %BLOG_NAME% was denied by network administrator. The requesting user has been notified via e-mail. For more information you can the network admin via %SUPERADMIN_EMAIL%.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager" );


define( 'MURM_MAIL_SUPERADMIN_TO_ADMIN_APPROVE_SUBJECT', '[%BLOG_NAME%] Registration request from user %USER_NAME% approved' );
define( 'MURM_MAIL_SUPERADMIN_TO_ADMIN_APPROVE_MESSAGE', "Network administrator has approved your registration request for user %USER_NAME% on your blog %BLOG_NAME%. The requesting user has been notified via e-mail. In case of any problems please contact the network admin via %SUPERADMIN_EMAIL%.\n\n--\n%SITE_NAME%\nMultisite User Registration Manager" );


define( 'MURM_MAIL_APPROVE_NEW_SUBJECT', '[%BLOG_NAME%] New user account: %USER_NAME%' );
define( 'MURM_MAIL_APPROVE_NEW_MESSAGE', "Your registration on the blog %BLOG_NAME% has been approved. Below you can find your login data.\n\nImportant information\n\n(1) This data is assigned to you and to you only. Do no share it with anyone!\n(2) If you request registration on another blog on site %SITE_NAME% in the future, use exactly the same combination of user name and e-mail (%USER_NAME%, %USER_EMAIL%).\n\nIn case of any problems or questions please contact the network administrator via  %SUPERADMIN_EMAIL%.\n\nUser name: %USER_NAME%\nE-mail: %USER_EMAIL%\nPassword: %PASSWORD%\n\nYou can log in here: %LOGIN_URL%.\n\nWelcome!\n\n--\n%SITE_NAME%\nMultisite User Registration Manager" );


define( 'MURM_MAIL_APPROVE_EXISTING_SUBJECT', '[%BLOG_NAME%] Registration of user account %USER_NAME% approved' );
define( 'MURM_MAIL_APPROVE_EXISTING_MESSAGE', "Your registration on the blog %BLOG_NAME% has been approved. For logging in use data sent to you earlier during creation of this user account.\n\nImportant information: If you request registration on another blog on site %SITE_NAME% in the future, use exactly the same combination of user name and e-mail (%USER_NAME%, %USER_EMAIL%).\n\nIn case of any problems or questions please contact the network administrator via  %SUPERADMIN_EMAIL%.\n\nYou can log in here: %LOGIN_URL%.\n\nWelcome!\n\n--\n%SITE_NAME%\nMultisite User Registration Manager" );


function murm_parse_mail( $message, $blog_id, $request_data, $password = '[not available]' ) {
	
	switch_to_blog( $blog_id );
	
	$patterns[0] = '/%BLOG_NAME%/';
	$replacements[0] = get_bloginfo( 'name' );
	
	$patterns[1] = '/%ADMIN_EMAIL%/';
	$replacements[1] = get_bloginfo( 'admin_email' );
	
	$patterns[2] = '/%LOGIN_URL%/';
	$replacements[2] = wp_login_url();
	
	restore_current_blog();
	
	$patterns[3] = '/%USER_EMAIL%/';
	$replacements[3] = $request_data->email;

	$patterns[4] = '/%SITE_NAME%/';
	$replacements[4] = get_site_option( 'site_name' );
	
	$patterns[5] = '/%SITE_URL%/';
	$replacements[5] = get_site_url( 1 );
	
	$patterns[6] = '/%SUPERADMIN_EMAIL%/';
	$replacements[6] = get_site_option( 'admin_email' );
	
	$patterns[7] = '/%PASSWORD%/';
	$replacements[7] = $password;
	
	$patterns[8] = '/%USER_NAME%/';
	$replacements[8] = $request_data->username;	
	
	$patterns[9] = '/%BLOG_URL%/';
	$replacements[9] = get_site_url( $blog_id );
	
	$result = preg_replace( $patterns, $replacements, $message );
	return $result;
}


function murm_is_current_user_email( $email ) {
	$userdata = get_userdata( get_current_user_id() );
	return ( $userdata->email == $email );
}



?>
