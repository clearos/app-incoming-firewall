<?php

/**
 * Firewall incoming allow controller.
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
 * Firewall incoming allow controller.
 *
 * @category   apps
 * @package    incoming-firewall
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/incoming_firewall/
 */

class Port_Range extends ClearOS_Controller
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

        $this->form_validation->set_policy('range_nickname', 'incoming_firewall/Incoming', 'validate_name', TRUE);
        $this->form_validation->set_policy('range_protocol', 'incoming_firewall/Incoming', 'validate_protocol', TRUE);
        $this->form_validation->set_policy('range_from', 'incoming_firewall/Incoming', 'validate_port', TRUE);
        $this->form_validation->set_policy('range_to', 'incoming_firewall/Incoming', 'validate_port', TRUE);

        // Handle form submit
        //-------------------

        if ($this->form_validation->run()) {
            try {
                $this->incoming->add_allow_port_range(
                    $this->input->post('range_nickname'),
                    $this->input->post('range_protocol'),
                    $this->input->post('range_from'),
                    $this->input->post('range_to')
                );

                $this->page->set_status_added();
                redirect('/incoming_firewall');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        // Load the view data 
        //------------------- 

        // Get the form data
        $data['protocols'] = $this->incoming->get_basic_protocols();

        // Load the views
        //---------------

        $this->page->view_form('incoming_firewall/allow/port_range', $data, lang('base_add'));
    }

    /**
     * Delete port rule.
     *
     * @param string  $protocol protocol
     * @param integer $port     port
     *
     * @return view
     */

    function delete($protocol, $port)
    {
        $confirm_uri = '/app/incoming_firewall/allow/destroy/' . $protocol . '/' . $port;
        $cancel_uri = '/app/incoming_firewall/allow';
        $items = array($protocol . ' ' . $port);

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }

    /**
     * Delete IPsec rule.
     *
     * @return view
     */

    function delete_ipsec()
    {
        $confirm_uri = '/app/incoming_firewall/allow/destroy_ipsec';
        $cancel_uri = '/app/incoming_firewall/allow';
        $items = array('IPsec');

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }

    /**
     * Delete PPTP rule.
     *
     * @return view
     */

    function delete_pptp()
    {
        $confirm_uri = '/app/incoming_firewall/allow/destroy_pptp';
        $cancel_uri = '/app/incoming_firewall/allow';
        $items = array('PPTP');

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }

    /**
     * Delete port range rule.
     *
     * @param string  $protocol protocol
     * @param integer $from     from port
     * @param integer $to       to port
     *
     * @return view
     */

    function delete_range($protocol, $from, $to)
    {
        $confirm_uri = '/app/incoming_firewall/allow/destroy_range/' . $protocol . '/' . $from . '/' . $to;
        $cancel_uri = '/app/incoming_firewall/allow';
        $items = array($protocol . ' ' . $from . ':' . $to);

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }

    /**
     * Destroys port rule.
     *
     * @param string  $protocol protocol
     * @param integer $port     port
     *
     * @return view
     */

    function destroy($protocol, $port)
    {
        // Load libraries
        //---------------

        $this->load->library('incoming_firewall/Incoming');

        // Handle form submit
        //-------------------

        try {
            $this->incoming->delete_allow_port($protocol, $port);

            $this->page->set_status_deleted();
            redirect('/incoming_firewall/allow');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Destroys port range rule.
     *
     * @param string  $protocol protocol
     * @param integer $from     from port
     * @param integer $to       to port
     *
     * @return view
     */

    function destroy_range($protocol, $from, $to)
    {
        // Load libraries
        //---------------

        $this->load->library('incoming_firewall/Incoming');

        // Handle form submit
        //-------------------

        try {
            $this->incoming->delete_allow_port_range($protocol, $from, $to);

            $this->page->set_status_deleted();
            redirect('/incoming_firewall/allow');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Destroys IPsec rule.
     *
     * @return view
     */

    function destroy_ipsec()
    {
        // Load libraries
        //---------------

        $this->load->library('incoming_firewall/Incoming');

        // Handle form submit
        //-------------------

        try {
            $this->incoming->set_ipsec_server_state(FALSE);

            $this->page->set_status_deleted();
            redirect('/incoming_firewall/allow');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Destroys PPTP rule.
     *
     * @return view
     */

    function destroy_pptp()
    {
        // Load libraries
        //---------------

        $this->load->library('incoming_firewall/Incoming');

        // Handle form submit
        //-------------------

        try {
            $this->incoming->set_pptp_server_state(FALSE);

            $this->page->set_status_deleted();
            redirect('/incoming_firewall/allow');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Disables port rule.
     *
     * @param string  $protocol protocol
     * @param integer $port     port
     *
     * @return view
     */

    function disable($protocol, $port)
    {
        try {
            $this->load->library('incoming_firewall/Incoming');

            $this->incoming->set_allow_port_state(FALSE, $protocol, $port);

            $this->page->set_status_disabled();
            redirect('/incoming_firewall/allow');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Disables range rule.
     *
     * @param string  $protocol protocol
     * @param integer $from     from port
     * @param integer $to       to port
     *
     * @return view
     */

    function disable_range($protocol, $from, $to)
    {
        try {
            $this->load->library('incoming_firewall/Incoming');

            $this->incoming->set_allow_port_range_state(FALSE, $protocol, $from, $to);

            $this->page->set_status_disabled();
            redirect('/incoming_firewall/allow');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Enables port rule.
     *
     * @param string  $protocol protocol
     * @param integer $port     port
     *
     * @return view
     */

    function enable($protocol, $port)
    {
        try {
            $this->load->library('incoming_firewall/Incoming');

            $this->incoming->set_allow_port_state(TRUE, $protocol, $port);

            $this->page->set_status_enabled();
            redirect('/incoming_firewall/allow');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }

    /**
     * Enables range rule.
     *
     * @param string  $protocol protocol
     * @param integer $from     from port
     * @param integer $to       to port
     *
     * @return view
     */

    function enable_range($protocol, $from, $to)
    {
        try {
            $this->load->library('incoming_firewall/Incoming');

            $this->incoming->set_allow_port_range_state(TRUE, $protocol, $from, $to);

            $this->page->set_status_enabled();
            redirect('/incoming_firewall/allow');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }
}
