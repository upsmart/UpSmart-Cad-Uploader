<?php
/**
 * Custom twitter widget function
 *
 * Credit:
 * Jeffrey Way - https://github.com/JeffreyWay/WordPress-Twitter-Widget
 * 
 * @package     Tiga
 * @author      Satrya
 * @license     license.txt
 * @since       Tiga 0.0.1
 *
 */

class tiga_twitter extends WP_Widget {
    function __construct() {
        $params = array(
	    'description' => __('Display your recent tweets to your readers.', 'tiga'),
	    'name' => __('&raquo; Tiga twitter', 'tiga')
		);
        
        // id, name, params
        parent::__construct('tiga_twitter', '', $params);
    }
    
    public function form($instance) {
        extract($instance);
        ?>
        
        <p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:', 'tiga'); ?> </label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if ( isset($title) ) echo esc_attr($title); ?>">
        </p>
        
        <p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter Username:', 'tiga'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" value="<?php if ( isset($username) ) echo esc_attr($username); ?>">
        </p>
        
        <p>
			<label for="<?php echo $this->get_field_id('tweet_count'); ?>">
				<?php _e('Number of Tweets to Retrieve:', 'tiga'); ?>
			</label>
			<input type="number" class="widefat" style="width: 40px;" id="<?php echo $this->get_field_id('tweet_count');?>" name="<?php echo $this->get_field_name('tweet_count');?>" min="1" max="10" value="<?php echo !empty($tweet_count) ? $tweet_count : 5; ?>" />
        </p>
        <?php
    }
    
    // What the visitor sees...
    public function widget($args, $instance) {
	extract($instance);
        extract( $args );
        
        if ( empty($title) ) $title = 'Recent Tweets';
        
        $data = $this->twitter($tweet_count, $username);
        if ( false !== $data && isset($data->tweets) ) {
            echo $before_widget;
		echo $before_title;
		    echo $title;
		echo $after_title;
		
		echo '<ul><li>' . implode('</li><li>', $data->tweets) . '</li></ul>'; ?>
		<span class="follow-me">
			<a href="https://twitter.com/<?php echo $username; ?>" class="twitter-follow-button" data-show-count="false">Follow @<?php echo $username; ?></a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</span>
        <?php   echo $after_widget;
        }
    }
    
    private function twitter($tweet_count, $username)
    {
        if ( empty($username) ) return;
        
        $tweets = get_transient('recent_tweets_widget');
        if ( !$tweets ||
	    $tweets->username !== $username ||
	    $tweets->tweet_count !== $tweet_count )
	{
	    return $this->fetch_tweets($tweet_count, $username);
	}
        return $tweets;
    }
    
    private function fetch_tweets($tweet_count, $username)
    {
	$tweets = wp_remote_get("http://twitter.com/statuses/user_timeline/$username.json");
	$tweets = json_decode($tweets['body']);
	
	// An error retrieving from the Twitter API?
	if ( isset($tweets->error) ) return false;
	
	$data = new StdClass();
	$data->username = $username;
	$data->tweet_count = $tweet_count;
	
	foreach($tweets as $tweet) {
	    if ( $tweet_count-- === 0 ) break;
	    $data->tweets[] = $this->filter_tweet( $tweet->text );
	}
	
	set_transient('recent_tweets_widget', $data, 60 * 5); // five minutes
	return $data;
    }

    private function filter_tweet($tweet)
    {
        // Username links
        $tweet = preg_replace('/(http[^\s]+)/im', '<a href="$1">$1</a>', $tweet);
        $tweet = preg_replace('/@([^\s]+)/i', '<a href="http://twitter.com/$1">@$1</a>', $tweet);
        // URL links
        return $tweet;
    }
    
}
?>