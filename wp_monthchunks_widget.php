<?php
/*
Plugin Name: WP Monthchunks widget
Plugin URI: https://github.com/yakumo-saki/wp_month_chunks_widget
Description: コンパクトな月表示ウィジェットを提供します
Version: 0.03
Author: Yakumo Saki
Author URI: https://github.com/yakumo-saki/wp_month_chunks_widget
*/
// thanks to http://weble.org/2011/04/19/wordpress-beginner-plugin

class MonthChunksWidget extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct(false, $name = 'MonthChunksWidget');
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

add_action('widgets_init', function() { register_widget("MonthChunksWidget");});
