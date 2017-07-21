<?php
/*
  Plugin Name: Selective parent Page Drop Down
  Description: This plugin will allow to selectively add a page to be selected as parent in the page attributes drop down box
  Version: 1.2
  Tested up to: WPMU 4.4.2
  License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
  Author: WPIndex
  Author URI: http://phpwpinfo.com
  Plugin URI:
  tags: page attributes, parent pages, pages
 */
global $wpdb;
include(plugin_dir_path(__FILE__) . "/parent_page_dropdown.php");
include(plugin_dir_path(__FILE__) . "/selective_ppd_settings.php");
$parent_page_dropdown_limit = new ParentPageDropDownLimit();

?>
