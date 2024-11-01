<?php
/*
Plugin Name: Worldlogger
Plugin URI: http://www.worldlogger.com/
Description: Track live web statistics with Worldlogger. Real time visitor tracking and social media monitoring.
Version: 1.0
Author: J. van der Meer
Author URI: http://www.worldlogger.com/
License: GPL2
*/


function worldlogger_admin_warnings() {
	global $wpcom_api_key;
	if ( !get_option('worldlogger_hash') && !isset($_POST['submit']) ) {
		function worldlogger_warning() {
			echo "
			<div id='worldlogger-warning' class='updated fade'><p><strong>".__('Worldlogger is almost ready.')."</strong> ".sprintf('You must <a href="%1$s">enter a Worldlogger API key</a> for it to work.', "options-general.php?page=worldlogger-options")."</p></div>
			";
		}
		add_action('admin_notices', 'worldlogger_warning');
		return;
	}
}

function worldlogger_init() {
	worldlogger_admin_warnings();
	add_option('worldlogger_hash');
}

function worldlogger_menu() {
	add_options_page('Worldlogger Options', 'Worldlogger', 'administrator', 'worldlogger-options', 'worldlogger_options');
}

function worldlogger_options() {
	  // variables for the field and option names 
	    $opt_name = 'worldlogger_hash';
	    $hidden_field_name = 'mt_submit_hidden';
	    $data_field_name = 'worldlogger_hash';

	    // Read in existing option value from database
	    $opt_val = get_option( $opt_name );

	    // See if the user has posted us some information
	    // If they did, this hidden field will be set to 'Y'
	    if( $_POST[ $hidden_field_name ] == 'Y' ) {
	        // Read their posted value
	        $opt_val = $_POST[ $data_field_name ];
	        // Save the posted value in the database
	        update_option( $opt_name, $opt_val );

	        // Put an options updated message on the screen

	?>
	<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
	<?php } ?>
	<div class="wrap">
	<h2><?= __( 'Worldlogger Options', 'worldlogger_domain' ) ?></h2>
	To install Worldlogger, please create an account at <a href="http://www.worldlogger.com/signup">worldlogger.com</a> and copy the hash in the "Settings" page of a website.
	<form name="form1" method="post" action="">
		<table class="form-table"> 
		<tr valign="top"> 
		<th scope="row"><label for="blogname">Worldlogger Hash</label></th> 
		<td><input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="40"></td> 
		</tr> 
		</table>
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<p class="submit"> 
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" /> 
		</p>
	</form>
	</div>

	<?php

}




function worldlogger_add_footer() {
	$hash = get_option('worldlogger_hash');
	if(!$hash)
		return;
?>  
    <script type="text/javascript">
			var wljs_http = "https:" == document.location.protocol ? "https://" : "http://";
    	var wljs_path = wljs_http + "ping.worldlogger.com/worldlogger.js";
    
    	document.write(unescape("%3Cscript src='"+wljs_path+"' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script>
    	document.write(unescape("%3Cscript%3Etry { var wl = new wl_WorldLogger('<?=$hash ?>', {'ping_location':wljs_http + 'ping.worldlogger.com/ping'}); } catch(e) { if(console) console.warn(e);}%3C/script%3E"));
    </script> 
<?
}


add_action('init', 'worldlogger_init');
add_action('admin_menu', 'worldlogger_menu');
add_action('wp_footer', 'worldlogger_add_footer');
?>