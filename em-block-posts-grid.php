<?php

/**
* Main plugin file.
* PHP version 7.3

* @category Wordpress_Plugin
* @package  Esmond-M
* @author   Esmond Mccain <esmondmccain@gmail.com>
* @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License
* @link     esmondmccain.com
* @return
*/
declare(strict_types=1);
namespace emBlockPostGrid;
/**
 * Plugin Name:       EM Posts Grid
 * Description:       Show posts in various formats
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Esmond Mccain
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       em-block-posts-grid
 *
 * @package EmBlockPostsGrid
 */

defined('ABSPATH') or die();
/**
 * Define global constants

 * @param $constant_name
 * @param $value
 *
 * @return array
 */
function emBlockPostGridConstants($constant_name, $value)
{
    $constant_name_prefix = 'EM_Block_PostGrid_Constants_';
    $constant_name = $constant_name_prefix . $constant_name;
    if (!defined($constant_name))
        define($constant_name, $value);
}

emBlockPostGridConstants('DIR', dirname(plugin_basename(__FILE__)));
emBlockPostGridConstants('BASE', plugin_basename(__FILE__));
emBlockPostGridConstants('URL', plugin_dir_url(__FILE__));
emBlockPostGridConstants('PATH', plugin_dir_path(__FILE__));
emBlockPostGridConstants('SLUG', dirname(plugin_basename(__FILE__)));
require  EM_Block_PostGrid_Constants_PATH
    . 'includes/classes/emBlockPostGrid.php';
use emBlockPostGrid\emBlockPostGrid;

new emBlockPostGrid;