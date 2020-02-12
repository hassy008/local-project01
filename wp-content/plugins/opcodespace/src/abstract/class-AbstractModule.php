<?php

abstract class AbstractModule{
    /**
     * @var wpdb
     */
    protected $db;

    /**
     * @var
     */
    protected $table;

    /**
     * AbstractModule constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->db->show_errors();
    }

    /**
     * @param $data
     * @return false|int
     */
    public function insert($data)
    {
        $status = $this->db->insert($this->table, $data);

        return empty($status) ? $status : $this->db->insert_id;

    }

    /**
     * @param $data
     * @param $where
     * @return false|int
     */
    public function update($data, $where)
    {
        return $this->db->update($this->table, $data, $where);
    }

    /**
     * @param $data
     * @param $where
     * @return false|int
     */
    public function updateOrInsert($data, $where)
    {
        $response = $this->getRow($where);

        if(! empty($response)){
            return $this->update($data, $where);
        }

        return $this->insert($data);

    }

    /**
     * @param $where
     * @return false|int
     */
    public function delete($where)
    {
        return $this->db->delete($this->table, $where);
    }


    /**
     * @param $order_by
     * @param int $limit
     * @param int $offset
     * @return array|null|object
     */
    public function getAll($order_by, $limit = 0, $offset = 0)
    {
        $query = "";

        if($limit > 0){
            $query = "LIMIT $limit OFFSET $offset";
        }

        return $this->db->get_results("SELECT * FROM $this->table ORDER BY $order_by $query");
    }


    /**
     * @param $where
     * @param int $limit
     * @param int $offset
     * @param $order_by
     * @return array|null|object
     */
    public function get($where, $limit = 0, $offset = 0, $order_by = null)
    {
        $query = "";

        if($limit > 0){
            $query = "LIMIT $limit OFFSET $offset";
        }

        if($order_by != null){
            $query .= "ORDER BY $order_by";
        }

        $where_query = "";

        $and = '';
        $cnt = 0;
        foreach ($where as $key => $value){
            if ($cnt > 0) $and = " AND";

            $where_query .= "$and $key = '$value' ";
            $cnt++;
        }

        return $this->db->get_results("SELECT * FROM $this->table WHERE $where_query $query");


    }

    /**
     * @param $where
     * @param int $limit
     * @param int $offset
     * @param null $order_by
     * @return mixed
     */
    public function getRow($where, $limit = 0, $offset = 0, $order_by = null)
    {
        return $this->get($where, $limit, $offset, $order_by)[0];
    }
}