<?php


class OpsSchedule extends AbstractModule
{
    protected $table;
    /**
     * Class constructor.
     */
    function __construct()
    {
        parent::__construct();
        // $this->table = $this->db->prefix . 'schedules';

        $this->table = $this->db->prefix . 'schedules';
        $this->bookings_table = $this->db->prefix . 'schedules';

    }

    public function scheduleList()
    {
        return $this->db->get_results("SELECT *  FROM  $this->table
                                    WHERE created_at 
                                    BETWEEN CURRENT_DATE()
                                    AND DATE_ADD(CURRENT_DATE(), INTERVAL 12 Month)");
    }

    public function booking()
    {
        return $this->db->get_results("SELECT b.*, schedules.id
                                    FROM $this->bookings_table AS  b  
                                    RIGHT JOIN Employees ON Orders.EmployeeID = Employees.EmployeeID
                                    ORDER BY Orders.OrderID;");
    }

    public function testBooking()
    {
        return $this->db->get_results(
                            "SELECT b.first_name, b.last_name, b.email, b.first_name, b.schedule_id,
                            CONVERT(date,b.created_at) AS Date,
                            s.event_name,
                            bh.status,
                            FROM bookings b
                            LEFT JOIN schedules s
                            ON s.id = b.schedule_id
                            LEFT JOIN bookings_history bh
                            ON b.id = bh.booking_id
                            ORDER BY id;");
    }

}





// WHERE created_at >= now() - interval 30 Day"); working




// ("SELECT time_from, time_to
// FROM $this->table
// WHERE created_at(DATE, '%Y-%m-%d %H:%M:%S') <= DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY)");   








    // WHERE  Mem_DOB >= Cast(current_timestamp As Date) AND Mem_DOB < DATEADD(d, 30, Cast(current_timestamp As Date) AND availability = 'Available' );"








    // SELECT  time_from, time_to 
    // FROM  $this->table 
    // WHERE DAYOFYEAR(created_at)-DAYOFYEAR(getdate())<=30");
    //WHERE  created_at >= Cast(current_timestamp As Date) AND created_at < DATEADD(d, 30, Cast(current_timestamp As Date))");
