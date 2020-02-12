<?php

/**
 *
 */
class OpsAjaxAction
{

    public static function init()
    {
        $self = new self();
        //add_action( "wp_ajax_delete_source_pdf_forms", array($self, 'test') );
        //add_action( "wp_ajax_nopriv_linked_banks", array($self, '') );

        add_action("wp_ajax_nopriv_get_state", array($self, 'getState'));

        add_action("wp_ajax_get_edit_employee", array($self, 'getEditEmployee'));
        add_action("wp_ajax_nopriv_get_edit_employee", array($self, 'getEditEmployee'));

        add_action("wp_ajax_delete_employee", array($self, 'deleteEmployee'));
        add_action("wp_ajax_nopriv_delete_employee", array($self, 'deleteEmployee'));
        //add_action("wp_ajax_nopriv_add_note", array($self, 'addNote'));

        add_action("wp_ajax_get_flight_note", array($self, 'getFlightNote'));
        add_action("wp_ajax_nopriv_get_flight_note", array($self, 'getFlightNote'));

        add_action("wp_ajax_add_note", array($self, 'addNote'));
        add_action("wp_ajax_nopriv_add_note", array($self, 'addNote'));

        add_action("wp_ajax_get_update_ajax", array($self, 'getUpdateAjax'));
        add_action("wp_ajax_update_ajax", array($self, 'updateAjax'));

        add_action("wp_ajax_delete_event_schedule", array($self, 'deleteEventSchedule'));

        add_action("wp_ajax_date_filter", array($self, 'dateFilter'));
    }

    public function dateFilter()
    {
        if (isset($_POST["from_date"], $_POST["to_date"])) {
            global $wpdb;
            $output = '';
            $query = $wpdb->get_results("SELECT * FROM wp_crud  
                                        WHERE created_at 
                                        BETWEEN '" . $_POST["from_date"] . "' 
                                        AND '" . $_POST["to_date"] . "' ");

            $output .= '<table class="table table-bordered">  
                       <tr>  
                            <th width="5%">ID</th>  
                            <th width="30%">Customer Name</th>  
                            <th width="43%">Item</th>  
                            <th width="10%">Value</th>  
                            <th width="12%">Order Date</th>  
                       </tr>  ';
            if ($query > 0) {
                foreach ($query as $row) {
                    $output .= '  
                            <tr>  
                                 <td>' . $row["first_name"] . '</td>  
                                 <td>' . $row["last_name"] . '</td>  
                                 <td>' . $row["email"] . '</td>  
                                 <td>$ ' . $row["mobile_phone"] . '</td>  
                                 <td>' . $row["country"] . '</td>  
                            </tr>  
                       ';
                }
            } else {
                $output .= '  
                       <tr>  
                            <td colspan="5">No Order Found</td>  
                       </tr>  
                  ';
            }
            $output .= '</table>';
            echo $output;
        }
    }

    public function deleteEventSchedule()
    {
        $id = $_POST['id'];
        $OpsSchedule = new OpsSchedule();
        $result = $OpsSchedule->delete(['id' => $id]);

        wp_send_json_success([$result => 'Done']);
    }

    public function updateAjax()
    {
        $OpsWooCrud = new OpsWooCrud();
        $data = [];
        $id = intval($_POST['id']);
        $data['first_name'] = $_POST['firstName'];
        $data['last_name'] = $_POST['lastName'];
        $data['home_address'] = $_POST['home_address'];
        $data['email'] = $_POST['email'];
        $data['messages'] = $_POST['message'];
        $data['mobile_phone'] = $_POST['mobile_phone'];
        $data['notes'] = $_POST['note_update'];

        $update = $OpsWooCrud->updateOrInsert($data, ['id' => $id]);
        if ($update !== false) {
            wp_send_json_success(['message' => 'Successfully Edited']);
        }
        wp_send_json_error(['message' => 'Something went wrong']);
    }

    public function getUpdateAjax()
    {
        $id = $_POST['get_ajax_id'];
        $OpsWooCrud = new OpsWooCrud();

        $response = $OpsWooCrud->getRow(['id' => $id]);

        wp_send_json_success(['update_ajax' => $response]);
    }

    public function getFlightNote()
    {
        $id = $_POST['id'];
        $OpsWooCrud = new OpsWooCrud();
        $response = $OpsWooCrud->getRow(['id' => $id]);

        wp_send_json_success(['flight_note' => $response]);
    }

    public function addNote()
    {

        /*         $data = [];
$id = intval($_POST['id']);
$data['notes'] = $_POST['add_note'];

$OpsWooCrud = new OpsWooCrud();
$OpsWooCrud->updateOrInsert($data, ['id' => $id]);
// echo '<pre>';
// print_r($OpsWooCrud);
// echo '</pre>';

wp_redirect($_POST['_wp_http_referer']);
exit(); */

        $id = $_POST['id'];
        $OpsWooCrud = new OpsWooCrud();
        $response = $OpsWooCrud->getRow(['id' => $id]);

        wp_send_json_success(['flight_note' => $response]);
    }

    // public function addNote()
    // {
    //     $id = $_POST['id'];
    //     $OpsWooCrud = new OpsWooCrud();
    //     $response = $OpsWooCrud->getRow(['id' => $id]);

    //     wp_send_json_success(['edit_employee' => $response]);
    // }

    public function deleteEmployee()
    {
        $OpsWooCrud = new OpsWooCrud();
        $delete = $OpsWooCrud->delete(['id' => $_POST['id']]);
        wp_send_json_success(['messages' => 'doneeee']);
    }

    public function getEditEmployee()
    {
        $id = intval($_POST['emp_id']);
        $OpsWooCrud = new OpsWooCrud();
        $response = $OpsWooCrud->getRow(['id' => $id]);

        wp_send_json_success(['edit_employee' => $response]);
    }

    public function test()
    {
        # code...

        wp_send_json_success(['test' => 'done']);
    }

    public function getState()
    {
        if (isset($_POST["country"])) {
            // Capture selected country
            $country = $_POST["country"];

            // Define country and city array
            $countryArr = array(
                "USA" => array("New Yourk", "Los Angeles", "California"),
                "India" => array("Mumbai", "New Delhi", "Bangalore"),
                "United Kingdom" => array("London", "Manchester", "Liverpool"),
            );

            // Display city dropdown based on country name
            if ($country !== 'Select') {
                echo "<label>City:</label>";
                echo "<select>";
                foreach ($countryArr[$country] as $value) {
                    echo "<option>" . $value . "</option>";
                }
                echo "</select>";
            }
        }
        die();
    }
}
