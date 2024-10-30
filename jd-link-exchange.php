<?php 
/*
Plugin Name: JD Link exchange
Version: 1.3
Plugin URI: http://www.jd-link.net
Description: Links Exchange automatic SEO - € 10 offered links to the activation of the widget
Author URI: http://www.jooky-devellopement.com
*/
if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}

if ( !function_exists( 'is_plugin_active_for_network' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( is_plugin_active_for_network(plugin_basename(__FILE__)) ) {
	deactivate_plugins( plugin_basename(__FILE__) );
	$exit_msg = __('Link Exchange is already installed', 'jd-link-exchange');
	exit($exit_msg);
}


define( 'JDL_VERSION', '1.3' );
define( 'JDL_PATH', trailingslashit(dirname(__FILE__)) );
define( 'JDL_DIR', trailingslashit(dirname(plugin_basename(__FILE__))) );
define( 'JDL_URL', plugin_dir_url(dirname(__FILE__)) . JDL_DIR );
define( 'JDL_CONTENT',str_replace('/plugins/jd-link-exchange/','',JDL_PATH));
define( 'JDL_UPLOAD', AH_CONTENT .'/uploads/');
define( 'JDL_POST', AH_CONTENT .'/uploads/jd-link-exchange/');
define( 'JDL_POST_URL', get_site_url() .'/uploads/jd-link-exchange/');





/*
register_activation_hook(__FILE__, 'JDL_install'); 
function JDL_install(){

}
*/

//////////////menu ///////////////////////

function JDL_menu() {
add_menu_page(__('JD Link Exchange', 'jd-link-exchange'), __('JD Link Exchange', 'jd-link-exchange'),  'administrator',  JDL_PATH. 'index.php', NULL, JDL_URL . 'assets/icon-24x24.png');
}


if(is_admin()){if ( is_super_admin() ) {
add_action('admin_menu', 'JDL_menu');	
}
}




add_action( 'init', 'JDL_register_shortcodes');

function JDL_register_shortcodes(){
   add_shortcode('jd-links', 'jd_add_link');
}



function jd_add_link( $atts, $content = null ) {
extract(shortcode_atts(array(
      'maxitems' => 50,
	  'category' => 'exchange'
   ), $atts));

include_once(ABSPATH . WPINC . '/rss.php');
$rss = fetch_rss('http://jd-link.net/out/'.get_option('JDL_idr').'/'.get_option('JDL_idl').'/'.$category.'/'.urlencode(urlencode('http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'')).'.xml');
$items = array_slice($rss->items, 0, $maxitems);


$tab='<ul>';
if (empty($items)):
    $tab .='<li><a href="http://www.jd-link.net" title="'.__('Free backlink','jd-link-exchange').'" target="_blank">'.__('Add link','jd-link-exchange').'</a></li>';
 else:
      foreach ( $items as $item ):

       $tab .='<li>
	   '.$item['key_before'].' <a href="'.$item['link'].'" title="'.$item['title'].'">'.$item['title'].'</a> '.$item['key_after'].'
        </li>';
      endforeach;
    endif;
 $tab .='</ul>';   
return  $tab;

}

//fonction classic

if( !function_exists( 'xml_server_api' )) {
      function xml_server_api($url,$array)
       {
	$args	=	http_build_query($array);
	$url	=	parse_url($url);

	if(isset($url['port']))
	{
		$port=$url['port'];
	}
	else
	{
		$port='80';
	}

	if(!$fp=fsockopen($url['host'], $port, $errno, $errstr))
	{
		//$out = false;
	}
	else
	{
		$size = strlen($args);
		$request = "POST ".$url['path']." HTTP/1.1\n";
		$request .= "Host: ".$url['host']."\n";
		$request .= "Connection: Close\r\n";
		$request .= "Content-type: application/x-www-form-urlencoded\n";
		$request .= "Content-length: ".$size."\n\n";
		$request .= $args."\n";
		$fput = fputs($fp, $request);
	        $resultat ="";

		while (!feof($fp))
		{
			$resultat .= fgets($fp, 512);
		}

		fclose($fp);
		//$out = true;
	}

	$debut_flux = strpos($resultat,'<?xml version="1.0" encoding="UTF-8"?>');
	$flux = substr($resultat,$debut_flux);
	return $flux;
	      }
}
if( !function_exists( 'post_xml' )) {
function post_xml($fichier,$item,$champs) {
   $chaine = $fichier;
      $tmp = preg_split("/<\/?".$item.">/",$chaine);
      for($i=1;$i<sizeof($tmp)-1;$i+=2)
         foreach($champs as $champ) {
            $tmp2 = preg_split("/<\/?".$champ.">/",$tmp[$i]);
            $tmp3[$i-1][] = @$tmp2[1];
         }
      return @$tmp3;
   }
}

function JDL_cache_rss( $seconds ) {
  return 84600;
}

include(JDL_PATH. 'include/fonction_jdlink.php');
include(JDL_PATH. 'widget.php');
?>