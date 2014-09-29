<?php

/**
 * Incoming firewall add rule by Service view.
 *
 * @category   apps
 * @package    incoming-firewall
 * @subpackage views
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/incoming_firewall/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.  
//  
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('base');
$this->lang->load('firewall');

///////////////////////////////////////////////////////////////////////////////
// Standard service
///////////////////////////////////////////////////////////////////////////////

echo form_open('incoming_firewall/service');
echo form_header(lang('firewall_standard_service'));

echo field_simple_dropdown('service', $services, $service, lang('firewall_service'));

echo field_button_set(
    array(
        form_submit_add('submit_standard', 'high'),
        anchor_cancel('/app/incoming_firewall')
    )
);

echo form_footer();
echo form_close();
