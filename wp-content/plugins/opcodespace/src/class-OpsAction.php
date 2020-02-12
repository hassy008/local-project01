<?php

/**
 *
 */

use Leafo\ScssPhp\Compiler;

require_once OP_PATH . "inc/vendor/leafo/scssphp/scss.inc.php";
require_once OP_PATH . 'inc/vendor/autoload.php';
//include the file that loads the PhpSpreadsheet classes
// require 'spreadsheet/vendor/autoload.php';

// use PhpOffice\PhpSpreadsheet\Chart\Chart;
// use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
// use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
// use PhpOffice\PhpSpreadsheet\Chart\Layout;
// use PhpOffice\PhpSpreadsheet\Chart\Legend;
// use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
// use PhpOffice\PhpSpreadsheet\Chart\Title;
// use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


// use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OpsAction
{
    public static function init()
    {
        $self = new self;
        //add_action('admin_post_', array($self, ''));
        // add_action('admin_post_nopriv_', array($self,'') );

        add_action('admin_post_php_word', array($self, 'phpWord'));
        add_action('admin_post_nopriv_php_word', array($self, 'phpWord'));

        add_action('admin_post_generate_test_csv', array($self, 'generateTestCsv'));
        add_action('admin_post_nopriv_generate_test_csv', array($self, 'generateTestCsv'));

        add_action('admin_post_woo_crud', array($self, 'wooCrud'));
        add_action('admin_post_nopriv_woo_crud', array($self, 'wooCrud'));

        add_action('admin_post_add_modal_crud', array($self, 'addModalCrud'));
        add_action('admin_post_nopriv_add_modal_crud', array($self, 'addModalCrud'));

        add_action('admin_post_add_note', array($self, 'addNote'));
        add_action('admin_post_nopriv_add_note', array($self, 'addNote'));

        add_action('admin_post_users_xlsx', array($self, 'usersXlsx'));
        add_action('admin_post_employee_xlsx', array($self, 'employeeXlsx'));

        add_action('admin_post_posts_csv', array($self, 'postsCsv'));

        //admin
        add_action('admin_post_users_list_from_admin', array($self, 'usersListFromAdmin'));
        //excel by template 
        add_action('admin_post_excel_by_template', array($self, 'excelByTemplate'));
        add_action('admin_post_excel_by_pie_chart', array($self, 'excelByPieChart'));

        add_action('admin_post_update_ajax', array($self, 'updateAjax'));
        add_action('admin_post_image_upload_by_excel', array($self, 'imageUploadByExcel'));

        ##
        add_action('admin_post_add_event_schedule', array($self, 'addEventSchedule'));
    }

    public function addEventSchedule()
    {
        $data = [];
        $data['user_id'] = $_POST['user_id'];

        $data['event_name'] = $_POST['event_name'];
        $data['date_day'] = $_POST['date_day'];

        // $data['time_from']  = date("Y-m-d H:i:s", strtotime(esc_sql($_POST['time_from'])));
        $data['time_from']  = esc_sql($_POST['time_from']);


        $data['time_to'] = $_POST['time_to'];
        $data['type'] = $_POST['type'];
        $data['availability'] = $_POST['availability'];
        $data['test_time'] = $_POST['test_time'];

        $OpsSchedule = new OpsSchedule();
        // $OpsSchedule->insert($data);
        echo '<pre>';
        print_r($OpsSchedule->insert($data));
        echo '</pre>';

        wp_redirect($_POST['_wp_http_referer']);
        exit();
    }

    public function imageUploadByExcel()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Paid');
        $drawing->setDescription('Paid');
        $drawing->setPath(__DIR__ . '\paid.jpg'); // put your path and image here
        $drawing->setCoordinates('B15');
        $drawing->setOffsetX(110);
        $drawing->setRotation(0);
        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $writer = new Xlsx($spreadsheet);
        // $writer->save('image.xlsx');

        $filename = 'Images-' . date("d-m-Y H:i:s") . '.xlsx';
        header('Content-Type: application/vnd.mx-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
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
        $data['country'] = $_POST['country'];

        $OpsWooCrud->updateOrInsert($data, ['id' => $id]);

        wp_redirect($_POST['_wp_http_referer']);
        exit();
    }

    public function excelByPieChart()
    {
        $spreadsheet = new Spreadsheet();

        //create an excel worksheet and add some data for chart
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->fromArray([
            ['', 2010, 2011, 2012],
            ['Q1', 12, 15, 21],
            ['Q2', 56, 73, 86],
            ['Q3', 52, 61, 69],
            ['Q4', 30, 32, 0],
        ]);

        //Set the Labels for each data series we want to plot
        $dataSeriesLabels = [
            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'Worksheet!$C$1', null, 1), //  2011
        ];

        //Set the X-Axis Labels
        $xAxisTickValues = [
            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'Worksheet!$A$2:$A$5', null, 4), //  Q1 to Q4
        ];

        //Set the Data values for each data series we want to plot
        $dataSeriesValues = [
            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'Worksheet!$C$2:$C$5', null, 4),
        ];

        //  Build the dataseries
        $series = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
            \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_PIECHART, // plotType
            null, // plotGrouping (Pie charts don't have any grouping)
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues          // plotValues
        );

        //  Set up a layout object for the Pie chart
        $layout = new \PhpOffice\PhpSpreadsheet\Chart\Layout();
        $layout->setShowVal(true);
        $layout->setShowPercent(true);

        //  Set the series in the plot area
        $plotArea = new \PhpOffice\PhpSpreadsheet\Chart\PlotArea($layout, [$series]);
        //  Set the chart legend
        $legend = new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, null, false);

        $title = new \PhpOffice\PhpSpreadsheet\Chart\Title('Test Pie Chart');

        //  Create the chart
        $chart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
            'chart', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            null, // xAxisLabel
            null   // yAxisLabel    - Pie charts don't have a Y-Axis
        );

        //Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A7');
        $chart->setBottomRightPosition('H20');

        //Add the chart to the worksheet
        $response =  $worksheet->addChart($chart);

        echo '<pre>';
        print_r($response);
        echo '</pre>';
        //Save Excel 2007 file
        // $filename ='excel-pie-chart.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        // $writer->save(__DIR__. $filename);

        // $filename = 'Pie-Chart-' . date("d-m-Y") . '.xlsx';
        // header('Content-Type: application/vnd.mx-excel');
        // header('Content-Disposition: attachment;filename="' . $filename . '"');
        // header('Cache-Control: max-age=0');

        // $writer->save('php://output');
    }

    public function excelByTemplate()
    {
        $OpsExport = new OpsExport();
        $results = $OpsExport->overviewMap();

        // $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(OP_ASSET_PATH . 'template/User-List.xlsx');
        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(__DIR__ . '\Requirements-Overvie.xlsx'); //for local sheet
        // $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(OP_VIEW_PATH . 'template/Requirements-Overview.xlsx');
        $sheet->setActiveSheetIndex(0);

        $activeSheet = $sheet->getActiveSheet();

        foreach ($results as $result) {
            $activeSheet->setCellValueExplicit($result['ex_column'] . '4', number_format(126852.36, 2, '.', ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        }

        // $activeSheet->setCellValueExplicit('B4', number_format(33525555.2, 2, '.', ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        // $activeSheet->setCellValueExplicit('c4', number_format(33525555.2, 2, '.', ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet);
        // $writer->save(__DIR__ . '/output.xlsx');Users-List
        // $writer->save(__DIR__ . '/Users-List-' . date("d-m-Y") . '.xlsx');

        $filename = 'Users-List-' . date("d-m-Y") . '.xlsx';

        header('Content-Type: application/vnd.mx-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function usersListFromAdmin()
    {
        $role = esc_attr($_GET['role']);

        $OpsUser = new OpsUser();
        $OpsExport = new OpsExport();

        $users  = $OpsUser->getUserByRole($role);
        $result  = $OpsExport->UsersListFromAdmin($users);
        // $result  = $OpsExport->UsersListFromAdmin($role);

        $timeStamp = date("Y-m-d_H:i:s");
        $filename = "List " . $role . '- ' . $timeStamp . ".xlsx";
        $OpsExport->xlsx($result, $filename);
    }

    public function postsCsv()
    {
        $OpsExport = new OpsExport();
        $employee = $OpsExport->postsCsv();
        $timeStamp = date("Y-m-d_H:i:s");
        $filename = "Employee List " . $timeStamp . ".xlsx";
        $OpsExport->xlsx($employee, $filename);
    }

    public function employeeXlsx()
    {
        $OpsExport = new OpsExport();
        $employee = $OpsExport->employeeXlsx();
        $timeStamp = date("Y-m-d_H:i:s");
        $filename = "Employee List " . $timeStamp . ".xlsx";
        $OpsExport->xlsx($employee, $filename);
    }

    public function usersXlsx()
    {
        $OpsExport = new OpsExport();
        $data = $OpsExport->test();
        $timeStamp = date("Y-m-d_H:i:s");
        $filename = "Users List" . $timeStamp . ".xlsx";
        $OpsExport->xlsx($data, $filename);
    }

    public function phpWord()
    {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Template.docx');

        $templateProcessor->setValue('date', date("d-m-Y"));
        $templateProcessor->setValue('name', 'John Doe');
        $templateProcessor->setValue(
            ['city', 'street'],
            ['Sunnydale, 54321 Wisconsin', '123 International Lane']
        );

        $templateProcessor->saveAs('MyWordFile.docx');
        // $templateProcessor->download('MyWordFile.docx');

        /*
$wordTest = new \PhpOffice\PhpWord\PhpWord();

$newSection = $wordTest->addSection();

$desc1 = "The Portfolio details is a very useful feature of the web page. You can eselected based on this details.";

$newSection->addText($desc1, array('name' => 'Tahoma', 'size' => 15, 'color' => 'red'));

$objectWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordTest, 'Word2007');
try {
$objectWriter->save(storage_path('TestWordFile.docx'));
} catch (Exception $e) {
}

return response()->download(storage_path('TestWordFile.docx')); */
    }

    public function addModalCrud()
    {
        if (!isset($_POST['add_modal_crud']) || !wp_verify_nonce($_POST['add_modal_crud'], 'add_modal_crud-nonce')) {
            die("You are not allowed to submit data.");
        }
        /*  $data = [
        'first_name'     => $_POST['fname'],
        'last_name'      => $_POST['lname'],
        'home_address'   => $_POST['haddress'],
        'email'          => $_POST['email'],
        //'mobile_phone'   => $_POST[''],
        'messages'       => $_POST['message']
        ]; */

        // $user_id            = get_current_user_id();
        // $user               = get_user_by('ID', $user_id);

        // $userdata = array(
        //     'first_name'    => $_POST['first_name'],
        //     'last_name'     => $_POST['last_name'],
        // );

        // if (!$user) {
        //     $userdata['user_login']  = (new OpsAuthentication)->generateUsername($_POST['first_name'] . $_POST['last_name']);
        //     $user_id = wp_insert_user($userdata);
        // } else {
        //     $user_id = $user->ID;
        //     wp_update_user($userdata);
        // }

        $data = [];
        $id = intval($_POST['id']);
        $data['first_name'] = $_POST['fname'];
        $data['last_name'] = $_POST['lname'];
        $data['home_address'] = $_POST['haddress'];
        $data['email'] = $_POST['email'];
        $data['messages'] = $_POST['message'];
        $data['mobile_phone'] = $_POST['phone'];
        $data['notes'] = $_POST['note_update'];
        $data['date'] = $_POST['date'];

        // if (!empty($date['date'])) {
        //     $dt = DateTime::createFromFormat("Y-m-d H:i", esc_sql($data['date']));
        //     $date = $dt->format('Y-m-d H:i:s');
        // }
        // $data['date'] = $date ? $date : '';

        $OpsWooCrud = new OpsWooCrud();
        //$OpsWooCrud->updateOrInsert($data, ['id' => $id]);

        $isUpdate = $OpsWooCrud->updateOrInsert($data, ['id' => $id]);

        $_SESSION['type'] = 'success';
        $_SESSION['message'] = 'Successfully saved Guest';
        if ($isUpdate === false) {
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Something went wrong';
        }
        wp_redirect($_POST['_wp_http_referer']);
        exit();
    }

    public function addNote()
    {
        // if (!isset($_POST['add_note']) || !wp_verify_nonce($_POST['add_note'], 'add_note-nonce')) {
        //     die("You are not allowed to submit data.");
        // }

        $data = [];
        $id = intval($_POST['emp_id']);
        $data['notes'] = $_POST['add_note'];

        $OpsWooCrud = new OpsWooCrud();
        $isUpdate = $OpsWooCrud->updateOrInsert($data, ['id' => $id]);

        $_SESSION['type'] = 'success';
        $_SESSION['message'] = 'Successfully saved Guest';
        if ($isUpdate === false) {
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Something went wrong';
        }

        wp_redirect($_POST['_wp_http_referer']);
        exit();
    }

    public function wooCrud()
    {
        if (!isset($_POST['woo_crud']) || !wp_verify_nonce($_POST['woo_crud'], 'woo_crud-nonce')) {
            die("You are not allowed to submit data.");
        }

        $data = [
            'first_name' => $_POST['fname'],
            'last_name' => $_POST['lname'],
            'home_address' => $_POST['haddress'],
            'office_address' => $_POST['oaddress'],
            'email' => $_POST['email'],
            //'mobile_phone'   => $_POST[''],
            'messages' => $_POST['message'],
        ];

        $OpsWooCrud = new OpsWooCrud();
        $OpsWooCrud->insert($data);
        // echo '<pre>';
        // print_r($OpsWooCrud);
        // echo '</pre>';
        wp_redirect($_POST['_wp_http_referer']);
        exit();
    }

    public function generateTestCsv()
    {
        // $spreadsheet =  new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet = new Spreadsheet();
        // // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', 'Hello World !')
        //     ->setCellValue('B1', 'Hello World  !')
        //     ->setCellValue('C1', 'Hello World C1 !')
        //     ->setCellValue('D1', 'Hello World D1 !');

        $arrayData = [
            [null, 2010, 2011, 2012],
            ['Q1', 12, 15, 21],
            ['Q2', 56, 73, 86],
            ['Q3', 52, 61, 69],
            ['Q4', 30, 32, 0],
        ];

        $spreadsheet->getActiveSheet()
            ->fromArray(
                $arrayData, // The data to set
                null, // Array values with this value will not be set
                'A1' // Top left coordinate of the worksheet range where
                //    we want to set these values (default is A1)
            );

        $writer = new Xlsx($spreadsheet);
        // $writer->save('hello world.xlsx'); // will create and save the file root

        $filename = "php-spreed-sheet";
        header('Content-Type: application/vnd.mx-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        header('Content-Control: max-age=0');

        $writer->save('php://output');
    }
}
