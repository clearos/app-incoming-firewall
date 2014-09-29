<?php

/**
 * Firewall incoming allow by Service controller.
 *
 * @category   apps
 * @package    incoming-firewall
 * @subpackage controllers
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
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\apps\firewall\Firewall as Firewall;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Firewall incoming allow by Service controller.
 *
 * @category   apps
 * @package    incoming-firewall
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/incoming_firewall/
 */

class Service extends ClearOS_Controller
{
    /**
     * Incoming allow overview.
     *
     * @return view
     */

    function index()
    {
        // Load libraries
        //---------------

        $this->load->library('incoming_firewall/Incoming');
        $this->lang->load('incoming_firewall');
        $this->lang->load('base');

        // Set validation rules
        //---------------------

        $this->form_validation->set_policy('service', 'incoming_firewall/Incoming', 'validate_service', TRUE);

        // Handle form submit
        //-------------------

        if ($this->form_validation->run()) {
            try {
                $this->incoming->add_allow_standard_service($this->input->post('service'));

                $this->page->set_status_added();
                redirect('/incoming_firewall');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        // Load the view data 
        //------------------- 

        // Create a list of already configured ports
        $services = $this->incoming->get_standard_service_list();
        $allow = $this->incoming->get_allow_ports();
        $exists = array();

        foreach ($allow as $details)
            $exists[] = $details['service'];

        // Get the form data
        $data['protocols'] = $this->incoming->get_basic_protocols();
        $data['services'] = array();

        foreach ($services as $service) {
            if (! in_array($service, $exists))
                $data['services'][] = $service;
        }

        // Load the views
        //---------------

        $this->page->view_form('incoming_firewall/allow/service', $data, lang('base_add'));
    }
}
