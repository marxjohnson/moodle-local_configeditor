<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Script for processing AJAX calls from the Configurator form
 *
 * @package    local_configeditor
 * @author      Mark Johnson <mark.johnson@tauntons.ac.uk>
 * @copyright   2011 Tauntons College, UK
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);
require_once('../../config.php');
require_once($CFG->dirroot.'/local/configeditor/lib.php');
use local_configeditor as ce;
require_login($SITE);
if (!is_siteadmin()) {
    header('HTTP/1.1 403 Forbidden');
    die(get_string('adminonly', 'local_configeditor'));
}

$function = required_param('function', PARAM_TEXT);
$plugin = required_param('plugin', PARAM_TEXT);

$output = new stdClass;
switch ($function) {
    case 'get_settings':
        $output->settings = ce\get_settings_for_plugin($plugin);
        break;

    case 'get_setting':
        $name = required_param('setting', PARAM_TEXT);
        $output->setting = get_config($plugin, $name);
        break;

    case 'save_setting':
        $name = required_param('setting', PARAM_TEXT);
        $value = required_param('value', PARAM_TEXT);
        $output->result = set_config($name, $value, $plugin);
        break;
}
echo json_encode($output);
