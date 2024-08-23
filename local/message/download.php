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
 * TODO describe file download
 *
 * @package    local_message
 * @copyright  2024 LMSCloud.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use local_message\manager as manager;
require_once(__DIR__ ."/../../config.php");

require_login();
$records = (new manager)->get_details();
// print_object($records);die;

$dataformat = optional_param('dataformat', 'csv', PARAM_ALPHA);
$columns = ['Id', 'Full Name', 'User Id', 'Time Created', 'Time Modified', 'Description', 'Profile Link'];
$exportdata = new ArrayObject($records);

$iterator = $exportdata->getIterator();
// print_object($iterator);die;
\core\dataformat::download_data('user_records', $dataformat, $columns, $iterator);
