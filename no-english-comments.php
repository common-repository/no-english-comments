<?php
/*

**************************************************************************

Plugin Name:  No English Comments
Plugin URI:   http://www.arefly.com/no-english-comments/
Description:  Disallow English Comments in Your Blog. 在你的部落格中禁止英文評論
Version:      1.0.6
Author:       Arefly
Author URI:   http://www.arefly.com/
Text Domain:  no-english-comments
Domain Path:  /lang/

**************************************************************************

	Copyright 2014  Arefly  (email : eflyjason@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

**************************************************************************/

define("NO_ENGLISH_COMMENTS_PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("NO_ENGLISH_COMMENTS_FULL_DIR", plugin_dir_path( __FILE__ ));
define("NO_ENGLISH_COMMENTS_TEXT_DOMAIN", "no-english-comments");

/* Plugin Localize */
function no_english_comments_load_plugin_textdomain() {
	load_plugin_textdomain(NO_ENGLISH_COMMENTS_TEXT_DOMAIN, false, dirname(plugin_basename( __FILE__ )).'/lang/');
}
add_action('plugins_loaded', 'no_english_comments_load_plugin_textdomain');

include_once NO_ENGLISH_COMMENTS_FULL_DIR."options.php";

/* Add Links to Plugins Management Page */
function no_english_comments_action_links($links){
	$links[] = '<a href="'.get_admin_url(null, 'options-general.php?page='.NO_ENGLISH_COMMENTS_TEXT_DOMAIN.'-options').'">'.__("Settings", NO_ENGLISH_COMMENTS_TEXT_DOMAIN).'</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'no_english_comments_action_links');

function no_english_comments($incoming_comment) {
	$epattern = '/[^A-Za-z]+/';
	if(preg_match($epattern, $incoming_comment['comment_content'])){
		$message = nl2br(get_option("no_english_comments_notice"));
		if (get_option("no_english_comments_mode") == "ajax") {
			err($message);
		}else{
			header("Content-type: text/html; charset=utf-8");
			wp_die($message);
		}
		exit;
	}
	return($incoming_comment);
}
add_filter('preprocess_comment', 'no_english_comments');
