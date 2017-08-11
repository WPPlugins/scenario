<?php
/*
Plugin Name: Convertize
Description: Install Convertize on your WordPress website in less then 10 seconds. Integrate unique tracking code of Convertize into every page of your website in one click.
Author: Convertize
Version: 1.0.4
Author URI: http://getscenario.com
License: GPLv2
Plugin URI: http://getscenario.com/#

*/

define('SCP_PLUGIN_DIR',str_replace('\\','/',dirname(__FILE__)));

if ( !class_exists( 'HeaderPixels' ) ) {
	
	class HeaderPixels {

		function __construct() {

			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			add_action( 'wp_head', array( &$this, 'wp_head' ) );

		
		}

	
		function admin_init() {
			register_setting( 'insert-convertize-pixel', 'scp_insert_header', 'trim' );

			foreach (array('post','page') as $type) 
			{
				add_meta_box('scp_all_post_meta', 'Insert Script to &lt;head&gt;', 'scp_meta_setup', $type, 'normal', 'high');
			}
			
			add_action('save_post','scp_post_meta_save');
		}


		function admin_menu() {

			$page = add_menu_page('Convertize', 'Convertize', 'manage_options', 'Convertize', array( &$this, 'scp_options_panel' ) , plugin_dir_url( __FILE__ ) . 'icon.png');
			}
	
		function wp_head() {
			$meta = get_option( 'scp_insert_header', '' );
				if ( $meta != '' ) {
					echo $meta, "\n";
				}

			$scp_post_meta = get_post_meta( get_the_ID(), '_inpost_head_script' , TRUE );
				if ( $scp_post_meta != '' ) {
					echo $scp_post_meta['convertize_header_script'], "\n";
				}
			
		}


		function scripts(){
			wp_enqueue_style('style', plugin_dir_url( __FILE__ ) . 'css/plugin-styles.css' );
			wp_enqueue_script('script', plugin_dir_url( __FILE__ ) . 'js/plugin-script.js');
		}

		function convertize_flush(){
			if (function_exists('w3tc_dbcache_flush')) { w3tc_dbcache_flush();}
			if (function_exists('w3tc_pgcache_flush')) {w3tc_pgcache_flush();}
			if (function_exists('wp_cache_clear_cache')) {wp_cache_clear_cache();}
			if (function_exists('rocket_clean_domain')) {rocket_clean_domain();}
		}



				
		function scp_options_panel() {

			$this->convertize_flush();
			$this->scripts();

				?>

			<div id="scp-wrap">
				<div class="wrap">
				<?php screen_icon(); ?>
					<h2>Connect Convertize to your website</h2>
					<hr />
					<div class="scp-wrap" style="width: auto;float: left;margin-right: 2rem;">

					
						<form name="dofollow" action="options.php" method="post">
						
							<?php settings_fields( 'insert-convertize-pixel' ); ?>
                        	
							<p style="padding-left: 5px;">Paste your <strong style="font-weight: 700;">unique tracking pixel</strong> below to connect Convertize to your website. With one click of a button this will add Convertize to every page of your WordPress site. Easy!</p>
                            <input  type="text" id="insert_header" class="<?php if(isset($_COOKIE["convertize_validation_class"])){ echo $_COOKIE["convertize_validation_class"]; }else{ echo "insert_header" ;} ?>" name="scp_insert_header" style="width:500px" value="<?php echo esc_html( get_option( 'scp_insert_header' ) ); ?>"/>
							</input>

                            <p class="info"><?php if(isset($_COOKIE["convertize_validation_text"])){ echo $_COOKIE["convertize_validation_text"]; }else{ echo "&nbsp;" ;} ?></p>

							<input class="button <?php if(isset($_COOKIE["convertize_button_class"])){ echo $_COOKIE["convertize_button_class"]; }else{ echo "button-primary" ;} ?>" type="submit" name="Submit" value="<?php if(isset($_COOKIE["convertize_button_text"])){ echo $_COOKIE["convertize_button_text"]; }else{ echo "Connect with Convertize" ;} ?>" />


						</form>
					</div>

				
				</div>
				</div>


				

			
				<?php
		}
	}

	function scp_meta_setup()
	{
		global $post;

		$meta = get_post_meta($post->ID,'_inpost_head_script',TRUE);
		echo '<input type="hidden" name="scp_post_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}
	 
	function scp_post_meta_save($post_id)
	{

		if ( ! isset( $_POST['scp_post_meta_noncename'] )
			|| !wp_verify_nonce($_POST['scp_post_meta_noncename'],__FILE__)) return $post_id;

		if ($_POST['post_type'] == 'page') 
		{
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		}
		else 
		{
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}

		$current_data = get_post_meta($post_id, '_inpost_head_script', TRUE);	
	 
		$new_data = $_POST['_inpost_head_script'];

		scp_post_meta_clean($new_data);
		
		if ($current_data) 
		{
			if (is_null($new_data)) delete_post_meta($post_id,'_inpost_head_script');
			else update_post_meta($post_id,'_inpost_head_script',$new_data);
		}
		elseif (!is_null($new_data))
		{
			add_post_meta($post_id,'_inpost_head_script',$new_data,TRUE);
		}

		return $post_id;
	}

	function scp_post_meta_clean(&$arr)
	{
		if (is_array($arr))
		{
			foreach ($arr as $i => $v)
			{
				if (is_array($arr[$i])) 
				{
					scp_post_meta_clean($arr[$i]);

					if (!count($arr[$i])) 
					{
						unset($arr[$i]);
					}
				}
				else 
				{
					if (trim($arr[$i]) == '') 
					{
						unset($arr[$i]);
					}
				}
			}

			if (!count($arr)) 
			{
				$arr = NULL;
			}
		}
	}

	
$scp_header_scripts = new HeaderPixels();

}


