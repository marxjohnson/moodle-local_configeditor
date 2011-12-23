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
 * Displays the form for selecting and editing config settings
 *
 * @package    local_configeditor
 * @author      Mark Johnson <mark.johnson@tauntons.ac.uk>
 * @copyright   2011 Tauntons College, UK
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot.'/local/configeditor/lib.php');
use local_configeditor as ce;
require_login($SITE);
if (!is_siteadmin()) {
    print_error(get_string('adminonly', 'local_configeditor'));
}
$PAGE->set_url('/local/configeditor/');
$PAGE->navbar->add(get_string('pluginname', 'local_configeditor'));

$jsmodule = array(
    'name'  =>  'local_configeditor',
    'fullpath'  =>  '/local/configeditor/module.js',
    'requires'  =>  array('base', 'node', 'io', 'json')
);
$PAGE->requires->js_init_call('M.local_configeditor.init', null, false, $jsmodule);

$plugins = ce\get_plugins();
$default = array('core' => get_string('core', 'local_configeditor'));
$pluginselect = html_writer::select($plugins, 'plugin', '', $default);
$coresettings = ce\get_settings_for_plugin();
$settingselect = html_writer::select($coresettings, 'setting');
$valueinput = html_writer::empty_tag('input', array('id' => 'valueinput', 'name' => 'value'));
$saveattrs = array(
    'id' => 'savebutton',
    'type' => 'button',
    'value' => get_string('savechanges'),
    'disabled' => 'disabled'
);
$savebutton = html_writer::empty_tag('input', $saveattrs);
$loadingicon = $OUTPUT->pix_icon('i/loading_small',
                                 get_string('loading', 'local_configeditor'),
                                 'moodle',
                                 array('id' => 'configeditor_loading'));
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'local_configeditor'));
echo $OUTPUT->box($OUTPUT->error_text(get_string('warning', 'local_configeditor')));
echo html_writer::tag('form', $pluginselect.$settingselect.$valueinput.$savebutton.$loadingicon);
echo $OUTPUT->footer();
