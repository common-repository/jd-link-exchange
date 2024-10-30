<div id="wpbody" role="main">

<div id="wpbody-content" aria-label="Contenu principal" tabindex="0">

  <?php 

extract($_POST);

$host=str_replace("www.","",$_SERVER['HTTP_HOST']);
$current_user = wp_get_current_user();
$user_login=$current_user->user_login;
$user_id=$current_user->ID;
$user_email=$current_user->user_email;
$user_info = get_userdata($current_user->ID);
$user_role =implode(', ', $user_info->roles);



echo '<h2><a href="http://www.jd-link.net" target="_blank"><img src="'.JDL_URL.'assets/icon-96x96.png" border="0" /></a>'.__('Welcome to JD link exchange','jd-link-exchange').'</h2>';


if(!isset($action)){
$action='null';
}
	if($action == "register"){
		$txt = '<h2>'.__("You are registered","jd-link-exchange").'</h2>';
		$txt .= '<p>'.__("Login","jd-link-exchange").' : '.$login.'</p>';
		$txt .= '<p>'.__("Pass","jd-link-exchange").' : '.$pass.'</p>';
		$txt .= '<p>'.__("Email","jd-link-exchange").' : '.$email.'</p>';
		$array =array (
		"site" => str_replace("www.","",$_SERVER['HTTP_HOST']),
		"action" => "register", 
		"login" => $login,
		"pass" => $pass,
		"email" => $email,
		"ip" => $_SERVER['REMOTE_ADDR'],
		"plugin" => "jd-link-exchange"
		); 
        $fluxl =xml_server_api('http://jd-link.net/cgi/register.php',$array);
		$xml2l = post_xml($fluxl,'item',array('resultat','detail','idl','idr'));
		//echo '<textarea name="txt" cols="150" rows="10">'.$fluxl.'</textarea>';	
		foreach($xml2l as $row) {
		if($row[0] == 1){	
add_option( 'JDL_idr',$row[3], 'yes' ); 
add_option( 'JDL_idl',$row[2], 'yes' ); 
add_option( 'JDL_login',$login, 'yes' ); 
add_option( 'JDL_pass',$pass, 'yes' ); 
add_option( 'JDL_email',$email, 'yes' ); 
update_user_meta( $user_id, 'idr', $row[3]);
$txt .= '<p>'.__("ID","jd-link-exchange").' : '.$row[2].'</p>';
$txt .= '<p>'.__("Affiliate ID ","jd-link-exchange").' : '.$row[3].'</p>';
$txt .= '<p>'.__("Login ","jd-link-exchange").' : '.$login.'</p>';
$txt .= '<p>'.__("Password ","jd-link-exchange").' : '.$pass.'</p>';
$txt .= '<p>'.__("Administration","jd-link-exchange").' : <a href="http://www.jd-link.net/wp-admin/" target="_blank">http://www.jd-link.net/wp-admin/</a></p>';
echo $txt;
wp_mail( $email, "You are regitered jd-link.net", $txt, $headers, ''); 

		} else {
echo '<div class="updated">'.$row[1].'</div>';			
		}

		}
	}
		if($action == "login"){
		$txt = '<h2>'.__("You are connected","jd-link-exchange").'</h2>';
		$array =array (
		"site" => str_replace("www.","",$_SERVER['HTTP_HOST']),
		"action" => "login", 
		"login" => $login,
		"pass" => $pass,
		"ip" => $_SERVER['REMOTE_ADDR'],
		"plugin" => "jd-link-exchange"
		); 
        $fluxl =xml_server_api('http://jd-link.net/cgi/register.php',$array);
		$xml2l = post_xml($fluxl,'item',array('resultat','detail','idl','idr'));	
		foreach($xml2l as $row) {
		if($row[0] == 1){	
add_option( 'JDL_idl',$row[2], 'yes' ); 
add_option( 'JDL_idr',$row[3], 'yes' ); 
add_option( 'JDL_login',$login, 'yes' ); 
add_option( 'JDL_pass',$pass, 'yes' ); 
add_option( 'JDL_email',$row[4], 'yes' ); 
update_user_meta( $user_id, 'idr', $row[3]);
$txt .= '<p>'.__("ID","jd-link-exchange").' : '.$row[2].'</p>';
$txt .= '<p>'.__("Affiliate ID ","jd-link-exchange").' : '.$row[3].'</p>';
$txt .= '<p>'.__("Administration","jd-link-exchange").' : <a href="http://www.jd-link.net/wp-admin/" target="_blank">http://www.jd-link.net/wp-admin/</a></p>';
echo $txt;
//wp_mail( $email, "You are regitered jd-link.net", $txt, $headers, ''); 

		} else {
echo '<div class="updated">'.$row[1].'</div>';			
		}
exit();
}


} 
		
		
if(!get_option('JDL_idr')) {
	
echo '<p>'.__("To start the exchange of links you must register (free) using the form below",'jd-link-exchange').' :</p>
<a href="http://jd-link.net<" target="_blank">'.__("You have a problem, register on the site",'jd-link-exchange').'</a><br>';
?>
  
<h3><?php _e('Do not have an account on jd-link.net? REGISTER',"jd-link-exchange");?></h3>    
<form id="form" action="?page=jd-link-exchange/index.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="register" />
<br /><table class="widefat" width="80%">
        <tbody>
<tr>
  <td><?php _e('Choice a pseudo',"jd-link-exchange");?></td>
  <td><input type="text" name="login"  size="75" value=""/></td>
</tr>
<tr>
  <td><?php _e('Choice password',"jd-link-exchange");?></td>
  <td><input type="password" name="pass"  size="75" value=""/></td>
</tr>
<tr>
  <td><?php _e('Your email',"jd-link-exchange");?></td>
  <td><input type="text" name="email"  size="75" value=""/></td>
</tr>
</tr>
                 </tbody>
                 </table> <br />  
<input name="submit" type="submit" value="<?php _e('register',"jd-link-exchange");?>" class="button button-green"/>
</form><br />

<h3><?php _e('You already have an account on jd-link.net, login',"jd-link-exchange");?></h3>    
<form id="form" action="?page=jd-link-exchange/index.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="login" />
<br /><table class="widefat" width="80%">
        <tbody>
<tr>
  <td><?php _e('Your pseudo',"jd-link-exchange");?></td>
  <td><input type="text" name="login"  size="75" value=""/></td>
</tr>
<tr>
  <td><?php _e('Your password',"jd-link-exchange");?></td>
  <td><input type="password" name="pass"  size="75" value=""/></td>
</tr>
</tr>
                 </tbody>
                 </table> <br />  
<input name="submit" type="submit" value="<?php _e('login',"jd-link-exchange");?>" class="button button-green"/>
</form><br />

      
       
 <?php } else  {
	 
echo '
			<div class="manage-menus">
 				<span class="add-edit-menu-action">
			'.__('Do not forget to activate the ','jd-link-exchange').' <a href="widgets.php">'.__('widget','jd-link-exchange').'</a> '.__('or add shortcode','jd-link-exchange').' &nbsp;[jd-links maxitems=50 category=exchange][/jd-links]</span>
			</div>
			<div id="nav-menus-frame">
			<div id ="menu-settings-column">
					<p>'.__("You are registered",'jd-link-exchange').'</p> 
<p>'.__("IDR",'jd-link-exchange').'  :  '.get_option('JDL_idr').'</p>
<p>'.__("IDL",'jd-link-exchange').'  :  '.get_option('JDL_idl').'</p>
<p>'.__("Login",'jd-link-exchange').'  :  '.get_option('JDL_login').'</p>
<a href="http://jd-link.net" target="_blank">http://jd-link.net</a><br>
<BR>
<div style="padding: 20px;background: #eeeeee;border: 1px solid #bbbbbb;border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px;">
	<p>'.__("Your credit",'jd-link-exchange').'</p> 
	'.JDL_solde(get_option('JDL_idl'),get_option('JDL_idr'),1).' €
</div> 
<br><br>
<button class="button-green" onclick="window.open(\'http://jd-link.net/wp-admin/?page=admin-hosting/admin-link/index.php&section=campaign&action=edit-campaign&action=add_campaign\', \'_blank\')">'.__('Add campaign','jd-link-exchange').'</button>
<br><br>
<button class="button-green" onclick="window.open(\'http://jd-link.net/wp-admin/?page=admin-hosting/admin-link/index.php&section=campaign\', \'_blank\')">'.__('View campaign','jd-link-exchange').'</button>
<br><br>
<button class="button-green" onclick="window.open(\'http://jd-link.net/wp-admin/?page=admin-hosting%2Fadmin-link%2Findex.php&section=add-credit\', \'_blank\')">'.__('Buy links','jd-link-exchange').'</button>



   </div>
   <div id="menu-management-liquid">
   
   <div style="width:40%;padding:0 10px 0 0;float:left;">
<h2>'.__('Page is displayed or your backlink','jd-link-exchange').'</h2>
'.JDL_list_backlnk_on_url(get_option('JDL_idl'),get_option('JDL_idr'),1).'
</div>
 
<div style="width:40%;padding:0 10px 0 0;float:right;">
<h2>'.__('Link on your website','jd-link-exchange').'</h2>
'.JDL_list_backlink(get_option('JDL_idl'),get_option('JDL_idr'),str_replace("www.","",$_SERVER['HTTP_HOST']),1).'
</div>
 
<div style="clear:both;"></div>
</div>

<div>';	 
	 
	       
	 
 }
 
 ?>
	   
	   
 </div>