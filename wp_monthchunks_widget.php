<?php
/*
Plugin Name: WP Monthchunks widget
Plugin URI: http://www.ziomatrix.org/download/wp_monthchunks_widget
Description: コンパクトな月表示ウィジェットを提供します
Version: 0.02
Author: marqut
Author URI: http://www.ziomatrix.org/
*/
 
// thanks to http://weble.org/2011/04/19/wordpress-beginner-plugin

class MonthChunksWidget extends WP_Widget {
    /** constructor */
    function MonthChunksWidget() {
        parent::WP_Widget(false, $name = 'MonthChunksWidget');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        global $wpdb;

        $title = apply_filters('widget_title', $instance['title']);
        
        // get months
        $result = $wpdb->get_results( "select distinct DATE_FORMAT(post_date, '%Y-%m') as ym FROM wp_posts WHERE post_status = 'publish' ORDER BY DATE_FORMAT(post_date, '%Y') desc,DATE_FORMAT(post_date, '%m') asc" );
        
        // 以下、表示テンプレート
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                  <?php
                  if (count($result) > 0) {
                      echo "<ul><li>";
                  }

                  // thisyear = ブレイクキー / next =>次のデータの年
                  $thisyear = substr($result[0]->ym,0,4); 
                  echo $thisyear . "<br />";
                  for ($i = 0; $i < count($result) - 1; $i++ ) {
                      // 年が変わっていた場合は年を出力
                      $nextyear = substr($result[$i]->ym,0,4);
                      if ( $thisyear != $nextyear) {
                          echo "</li>\n<li class='cat-item'>" . $nextyear . "<br />";
                          $thisyear = $nextyear;
                      }
                      $thismonth = substr($result[$i]->ym,5,2);
                      echo "<a href='" . get_month_link($thisyear, $thismonth) ."'>" . $thismonth . "</a>&nbsp;";
                  }

                  echo "</li></ul>";

                  ?>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php _e('Title:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
                </label>
            </p>
        <?php 
    }

} // class end

add_action('widgets_init', create_function('', 'return register_widget("MonthChunksWidget");'));
?>
