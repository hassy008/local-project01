<?php

require_once OP_PATH . 'inc/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OpsExport
{
    protected $table;
    protected $db;

    /**
     * Class constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }



    public function xlsx($data, $filename, $direct_download = true, $path = "")
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()
            ->fromArray(
                $data,
                null,
                'A1'
            );
        //$spreadsheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        if ($direct_download) {
            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.mx-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        } else {
            $writer->save($path, $filename);
        }
    }

    public function UsersListFromAdmin($users)
    {

        $report = [];
        $report[] = ['F Name', 'L Name', 'Email'];

        foreach ($users as $UsersList_key => $UsersList_value) {
            $arr = [];

            $arr[] = get_user_meta($UsersList_value->ID, 'first_name', true);
            $arr[] = get_user_meta($UsersList_value->ID, 'last_name', true);
            $arr[] = $UsersList_value->user_email;

            $report[] = $arr;
        }
        return $report;
    }

    public function postsCsv()
    {
        global $post;

        $arg = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );

        $arr_posts = get_posts($arg);

        $header[] = ['Post Title', 'URL', 'Author'];

        foreach ($arr_posts as $post) {
            setup_postdata($post);
            $arr = [];
            $arr = [get_the_title(), get_the_permalink(), get_the_author_meta('display_name')];

            $header[] = $arr;
        }
        return $header;
    }

    public function employeeXlsx()
    {
        $OpsWooCrud = new OpsWooCrud();
        $emplyoees = $OpsWooCrud->getAllData();

        $header[] = ['Name', 'Email', 'Address', 'Message'];

        foreach ($emplyoees as $emplyoee) {
            $body = [];
            // $body[] = $emplyoee->first_name . ' ' . $emplyoee->last_name;
            // $body[] = $emplyoee->email;
            // $body[] = $emplyoee->home_address;
            // $body[] = $emplyoee->messages;
            $body = [$emplyoee->first_name . ' ' . $emplyoee->last_name, $emplyoee->email, $emplyoee->home_address, $emplyoee->messages];

            $header[] =  $body;
        }
        return $header;
    }

    public function test()
    {
        $args = [
            'role' => 'administrator',
        ];

        $my_query = new WP_User_Query($args);
        $admins = $my_query->get_results();

        //header
        $header[] = ['First Name', 'Last Name', 'Email'];

        //body
        foreach ($admins as $admin) {

            $arr = [];
            $arr[] = get_user_meta($admin->ID, 'first_name', true);
            $arr[] = get_user_meta($admin->ID, 'last_name', true);
            $arr[] = $admin->user_email;

            $header[] = $arr;
        }
        return $header;
    }

    public function overviewMap()
    {
        return [
            [
                'ex_column' => 'A',
                'db_column' => 'email',
            ],
            [
                'ex_column' => 'B',
                'db_column' => 'last_name',
            ],
            [
                'ex_column' => 'C',
                'db_column' => 'first_name',
            ],
            [
                'ex_column' => 'D',
                'db_column' => 'skal_member_no',
            ],
            [
                'ex_column' => 'E',
                'db_column' => 'club',
            ],
            [
                'ex_column' => 'F',
                'db_column' => 'country',
            ],
            [
                'ex_column' => 'G',
                'db_column' => 'sex',
            ],
            [
                'ex_column' => 'H',
                'db_column' => 'language',
            ],
            [
                'ex_column' => 'I',
                'db_column' => 'accomodation_required',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'J',
                'db_column' => 'congress_registration_costs',
                'value' => 'I pay personally',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'K',
                'db_column' => 'congress_registration_costs',
                'value' => 'Skal (club, country, area, International) pays',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'L',
                'db_column' => 'congress_registration_costs',
                'value' => 'An other organization pays',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'M',
                'db_column' => 'congress_registration_costs',
                'value' => 'My company pays',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'N',
                'db_column' => 'accommodation_costs',
                'value' => 'I pay personally',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'O',
                'db_column' => 'accommodation_costs',
                'value' => 'Skal (club, country, area, International) pays',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'P',
                'db_column' => 'accommodation_costs',
                'value' => 'An other organization pays',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'Q',
                'db_column' => 'accommodation_costs',
                'value' => 'My company pays',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'R',
                'db_column' => 'is_first_timer',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'S',
                'db_column' => 'is_dietary_restrictions',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'T',
                'db_column' => 'is_food_allergies',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'U',
                'db_column' => 'wheelchair',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'V',
                'db_column' => 'preference_note',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'W',
                'db_column' => 'is_show_flight',
                'value' => '1',
                'type' => 'flight'
            ],
            [
                'ex_column' => 'X',
                'db_column' => 'is_show_flight',
                'value' => '0',
                'type' => 'flight'
            ],
            [
                'ex_column' => 'Y',
                'db_column' => 'is_show_flight',
                'value' => '2',
                'type' => 'flight'
            ],
            [
                'ex_column' => 'Z',
                'db_column' => 'flight_note',
                'type' => 'flight'
            ],
            [
                'ex_column' => 'AA',
                'db_column' => 'pre_nights',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'AB',
                'db_column' => 'post_nights',
                'type' => 'onoff'
            ],
            [
                'ex_column' => 'AC',
                'db_column' => 'congress',
                'type' => 'congress'
            ],
            [
                'ex_column' => 'AD',
                'db_column' => 'Additional Hotel Nights',
                'type' => 'amount'
            ],
            [
                'ex_column' => 'AE',
                'db_column' => '39',
                'type' => 'amount'
            ],
            [
                'ex_column' => 'AF',
                'db_column' => '40',
                'type' => 'amount'
            ],
            [
                'ex_column' => 'AG',
                'db_column' => '41',
                'type' => 'amount'
            ],
            [
                'ex_column' => 'AH',
                'db_column' => '43',
                'type' => 'amount'
            ],
            [
                'ex_column' => 'AI',
                'db_column' => '36',
                'type' => 'amount'
            ],
            [
                'ex_column' => 'AJ',
                'db_column' => '37',
                'type' => 'amount'
            ],
            [
                'ex_column' => 'AK',
                'db_column' => '38',
                'type' => 'amount'
            ],
        ];
    }
}