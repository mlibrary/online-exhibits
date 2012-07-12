<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Coins
 */


// Coins class uses some theme helper functions, so need to include this.
require_once HELPERS;

add_plugin_hook('public_append_to_items_show', 'coins');
add_plugin_hook('admin_append_to_items_show_primary', 'coins');
add_plugin_hook('public_append_to_items_browse_each', 'coins');
add_plugin_hook('admin_append_to_items_browse_simple_each', 'coins');
add_plugin_hook('admin_append_to_items_browse_detailed_each', 'coins');

require_once dirname(__FILE__) . '/functions.php';
