<?php
class JDL_Link_exchange_Widget extends WP_Widget {
    function __construct() {
        $widget_ops = array(
            'classname' => 'JDL_widget_class',
            'description' => __('JD Link exchange', 'jd-link-exchange')
        );
        $this->WP_Widget('JDL_widget_class', __('JD Link exchange', 'jd-link-exchange'), $widget_ops);
		
    }

    function form($instance) {
        $defaults = array(
            'title' => __('Partners', 'jd-link-exchange'),
			'format' => __('horizontal', 'jd-link-exchange'),
			'category' => 'exchange',
			'maxitems' => '10',
        );


        $instance = wp_parse_args((array) $instance, $defaults);
        $title = strip_tags($instance['title']);
		$format = strip_tags($instance['format']);
		$category = strip_tags($instance['category']);
		$maxitems = strip_tags($instance['maxitems']);
        ?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'jd-link-exchange'); ?> :</label> 
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<p><label for="<?php echo $this->get_field_id('format'); ?>"><?php  _e('Choose format', 'jd-link-exchange'); ?></label> 
<select name="<?php echo $this->get_field_name('format'); ?>"  id="<?php echo $this->get_field_id('format'); ?>" >
<option value="<?php echo esc_attr($format); ?>" selected="selected"><?php echo esc_attr($format); ?></option>
  <option value="vertical">vertical</option>
  <option value="horizontal">horizontal</option>
</select>
</p>
<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php  _e('Category', 'jd-link-exchange'); ?></label> 
<select name="<?php echo $this->get_field_name('category'); ?>"  id="<?php echo $this->get_field_id('category'); ?>" >
<option value="<?php echo esc_attr($category); ?>" selected="selected"><?php echo esc_attr($category); ?></option>
  <option value="mywebsite">><?php  _e('My website', 'jd-link-exchange'); ?></option>
  <option value="exchange">><?php  _e('Exchange website', 'jd-link-exchange'); ?></option>
</select>
</p>
<p><label for="<?php echo $this->get_field_id('maxitems'); ?>"><?php _e('Maxitems', 'jd-link-exchange'); ?> :</label> 
<input class="widefat" id="<?php echo $this->get_field_id('maxitems'); ?>" name="<?php echo $this->get_field_name('maxitems'); ?>" type="text" value="<?php echo esc_attr($maxitems); ?>" /></p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
		$instance['format'] = strip_tags($new_instance['format']);
		$instance['category'] = strip_tags($new_instance['category']);
		$instance['maxitems'] = strip_tags($new_instance['maxitems']);
        return $instance;
    }

        

    function widget($args, $instance) {
        extract($args);
        echo $before_widget;
        $title = apply_filters('widget_title', $instance['title']);
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

include_once(ABSPATH . WPINC . '/rss.php');
$rss = fetch_rss('http://jd-link.net/out/'.get_option('JDL_idr').'/'.get_option('JDL_idl').'/'.$instance['category'].'/'.urlencode(urlencode('http://'.$_SERVER['HTTP_HOST'].''.$_SERVER['REQUEST_URI'].'')).'.xml');
$maxitems= $instance['maxitems'];
$items = array_slice($rss->items, 0, $maxitems);

		if($instance['format'] == "vertical"){
$tab='<ul>';
if (empty($items)):
    $tab .='<li><a href="http://www.jd-link.net" title="'.__('Free backlink','jd-link-exchange').'" target="_blank">'.__('Add link','jd-link-exchange').'</a></li>';
 else:
      foreach ( $items as $item ):

         $tab .='<li> '.$item['key_before'].'
          <a href="'.$item['link'].'" title="'.$item['title'].'">
          '.$item['title'].'
          </a>
		  '.$item['key_after'].'
        </li>';
      endforeach;
    endif;
 $tab .='</ul>';   

		} else {
$tab = '<table border="0">';
$tab .='<tr>';
if (empty($items)):
    $tab .='<td><a href="http://www.jd-link.net" title="'.__('Free backlink','jd-link-exchange').'" target="_blank">'.__('Add link','jd-link-exchange').'</a></td>';
 else:
      foreach ( $items as $item ):

         $tab .='<td>
		 '.$item['key_before'].' <a href="'.$item['link'].'" title="'.$item['title'].'">'.$item['title'].'</a> '.$item['key_after'].'
        </td>';
      endforeach;
    endif;
 $tab .='';   
       $tab .= "<tr></table>";	
			
		}
        echo $tab;  


        echo $after_widget;
    }



}
function JDL_widgets_init() {
    register_widget('JDL_Link_exchange_Widget');
}
add_action('widgets_init', 'JDL_widgets_init');
?>