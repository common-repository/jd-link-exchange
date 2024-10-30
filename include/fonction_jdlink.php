<?php
include_once(ABSPATH . WPINC . '/rss.php');
function JDL_list_backlnk_on_url($user_id,$idr,$mj=''){
if($mj=='1'){
	$mj='&mj='.date('Ymdis').'';
}
$tab = '<table cellspacing="0" cellpadding="0" width="90%" class="paginate50 sortable full">
		<thead>
			<tr>
				<th align="left">'.__("Page","admin-hosting").'</th>
			</tr>
		</thead>
		<tbody>';
		
$rss = fetch_rss('http://jd-link.net/cgi/wordpress_campaign.php?idl='.$user_id.'&idr='.$idr.'&url='.str_replace("www.","",$_SERVER['HTTP_HOST']).'&action=view_backlink_url_acheteur'.$mj.'');
$items = array_slice($rss->items, 0, $maxitems);
foreach ( $items as $item ){
$tab .= '<tr >
				<td><a href="'.$item['page'].'" target="_blank">'.substr($item['page'],0,75).'</a></td>
			</tr>';
}

$tab .= '</tbody>
	</table>';
return $tab;
    }
    
 
function JDL_list_backlink($user_id,$idr,$url,$mj=''){ 
if($mj=='1'){
	$mj='&mj='.date('Ymdis').'';
}
 $tab = '<table cellspacing="0" cellpadding="0" width="90%" class="paginate50 sortable full">
		<thead>
			<tr>
				<th align="left">'.__("Links","admin-hosting").'</th>
			</tr>
		</thead>
		<tbody>';
$nb=0;
$rss = fetch_rss('http://jd-link.net/cgi/wordpress_campaign.php?idl='.$user_id.'&idr='.$idr.'&action=view_backlink_url_vendeur&url='.str_replace("www.","",$_SERVER['HTTP_HOST']).''.$mj.'');	
$items = array_slice($rss->items, 0, $maxitems);
foreach ( $items as $item ){
$tab .= '<tr ><td valign="top">- '.$item["key_before"].'&nbsp;<a href="'.$item["link"].'" target="_blank" title="'.$item["description"].'">'.$item["title"].'</a>&nbsp;'.$item["key_after"].'</td></tr>';
}
				 
$tab .= '</tbody>
	</table>';
return $tab;
} 

function JDL_solde($user_id,$idr,$mj=''){ 
if($mj=='1'){
	$mj='&mj='.date('Ymdis').'';
}
$total=0;
$rss = fetch_rss('http://jd-link.net/cgi/wordpress_event.php?action=solde&idl='.$user_id.'&idr='.$idr.'&site_client='.str_replace("www.","",$_SERVER['HTTP_HOST']).''.$mj.'');	
$items = array_slice($rss->items, 0, $maxitems);
foreach ( $items as $item )	
{
	$total=$item["total"];
}
return $total;
}

function JDL_login($url){
	
	$args = array(
	'echo'           => false,
	'remember'       => true,
	'redirect'       => $url,
	'form_id'        => 'loginform',
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_remember'    => 'rememberme',
	'id_submit'      => 'wp-submit',
	'label_username' => __( 'Username' ),
	'label_password' => __( 'Password' ),
	'label_remember' => __( 'Remember Me' ),
	'label_log_in'   => __( 'Log In' ),
	'value_username' => get_option('JDL_login'),
	'value_remember' => true
);

return wp_login_form( $args ); 

}
?>
   