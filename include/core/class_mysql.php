<?php
/**
 * Class db - класс для работы с БД mysql 
 */
class mysql
{
    public $db_id = false;
    public $mysqli_error = '';
    public $mysql_error_num = 0;
    public $query_id = false;
    
    /**
     * db constructor.
     * @param string $db_location
     * @param $db_user
     * @param $db_pass
     * @param $db_name
     * @param string $collate
     * @param $show_error
     */
    public function __construct($db_location = 'localhost', $db_user, $db_pass, $db_name, $collate = 'cp1251', $show_error)
    {
        $this->db_id = @mysqli_connect($db_location, $db_user, $db_pass, $db_name);
        if (!$this->db_id) {
            if ($show_error) {
                $this->display_error(mysqli_connect_error(), 1, 'CONNECT');
            }
        }
        mysqli_query($this->db_id, "SET NAMES '{$collate}'");
        return true;
    }

    /**
     * @param $query
     * @param bool $show_error
     * @return bool|mysqli_result
     */
    public function query($query, $show_error = true)
    {

        if (!($this->query_id = mysqli_query($this->db_id, $query))) {

            $this->mysqli_error = mysqli_error($this->db_id);
            $this->mysql_error_num = mysqli_errno($this->db_id);
            if ($show_error) {
                $this->display_error($this->mysqli_error, $this->mysql_error_num, $query);
            }
        }
        return $this->query_id;
    }

    /**
     * @param string $query_id
     * @return array|null
     */
    private function get_row($query_id = '')
    {
        if ($query_id == '')
            $query_id = $this->query_id;

        return mysqli_fetch_assoc($query_id);
    }

    /**
     * @param $query
     * @param bool $multi
     * @return array|null
     */
    public function super_query($query, $multi = false)
    {
        if (!$multi) {
            $this->query($query);
            $data = $this->get_row();
            $this->free();

            return $data;
        } else {
            $this->query($query);
            $rows = array();
            while ($row = $this->get_row()) {
                $rows[] = $row;
            }
            $this->free();

            return $rows;
        }
    }

    /**
     * @return int|string - id созданого поля
     */
    public function insert_id()
    {
        return mysqli_insert_id($this->db_id);
    }

    /**
     * @param string $query_id
     */
    private function free($query_id = '')
    {
        if ($query_id == '')
            $query_id = $this->query_id;
        @mysqli_free_result($query_id);
    }

    /**
     * @return close
     */
    public function close()
    {
        @mysqli_close($this->db_id);
    }
    
    /**
     * @param $error
     * @param $error_num
     * @param string $query
     */
    private function display_error($error, $error_num, $query = '')
    {
        die(view('system/error_page.tpl', ['msg' => $query, 'title' => 'Mysql Error: ' .  $error]));
    }
}