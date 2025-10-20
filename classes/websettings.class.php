<?php


class websettings
{
    private $_db;

    protected $_fetcheddata;

    private $_link_home;

    public function __construct()
    {
        $this->_db = db::getinstanace();
        $this->_link_home = config::get('website/website_url');
    }


    // START OF THE STANDARD CLASS FUNCTIONS

    public function add(string $logical_table_name, array $fields): bool
    {
        if (!$this->_db->insert($logical_table_name, $fields)) {
            throw new Exception('There was a problem adding data to the table: ' . $logical_table_name . '.');
        }
        return true;
    }

    public function lastInsertId()
    {
        return $this->_db->last_insert_id();
    }

    public function update(string $logical_table_name, array $fields, array $conditions): bool
    {
        if (!$this->_db->update($logical_table_name, $fields, $conditions)) {
            throw new exception('There was a problem in updating data to the table: ' . $logical_table_name . '.');
        }
        return true;
    }

    public function delete(string $logical_table_name, array $conditions)
    {
        if (!$this->_db->delete($logical_table_name, $conditions)) {
            throw new exception('There was a problem in deleting data to the table: ' . $logical_table_name . '.');
        }
        return true;
    }

    public function fetch(string $logical_table_name, $conditions = array(), $orderby = '', $limit = '', $offset = '')
    {
        $data = $this->_db->get($logical_table_name, $conditions, $orderby, $limit, $offset);

        if ($data->count() == 1) {
            return $data->first();
        } elseif ($data->count() > 1) {
            return $data->results();
        } else {
            return false;
        }
    }

    public function fetchbyarray(string $logical_table_name, $conditions = array(), $orderby = '', $limit = '', $offset = '')
    {
        $data = $this->_db->get($logical_table_name, $conditions, $orderby, $limit, $offset);

        if ($data->count()) {
            return $data->results();
        } else {
            return false;
        }
    }

    public function fetchbyjson($columns, string $logical_table_name, $joins = [], $conditions = [], $groupby = '', $orderby = '', $limit = '', $offset = '')
    {
        $data = $this->_db->select(
            $columns,
            $logical_table_name,
            $joins,
            $conditions,
            $groupby,
            $orderby,
            $limit,
            $offset
        );

        if ($data->count()) {
            $this->_fetcheddata = $data->results();
        } else {
            $this->_fetcheddata = [];
        }

        return true;
    }

    public function fetchedjson()
    {
        $response = [
            'totalrecords' => $this->get_total_count() ?? 0,
            'pagetotal' => $this->get_count() ?? 0,
            'data' => $this->_fetcheddata ?? []
        ];
        return json_encode($response);
    }

    public function get_pagination($totalcount, $pagecount, $currentpage, $pageroute, $querystring)
    {
        $pagination = new pagination(array(
            'total_records' => $totalcount,
            'per_page' => $pagecount,
            'current_page_no' => $currentpage,
            'url_append' => config::get('website/website_url') . $pageroute,
            'query_string' => str_replace('page=' . $currentpage . '&', '', $querystring)
        ));

        return $pagination->pagination();
    }



    public function begin_transaction()
    {
        return $this->_db->begin_transaction();
    }

    public function commit()
    {
        return $this->_db->commit();
    }
    public function roll_back()
    {
        return $this->_db->roll_back();
    }

    public function get_last_insert_id()
    {
        return $this->_db->last_insert_id();
    }

    public function get_count()
    {
        return $this->_db->count();
    }

    public function get_total_count()
    {
        return $this->_db->total_count();
    }



    ////////////////// END OF THE STANDARD CLASS FUNCTIONS////////////////////////////////



    public function get_combo($obj = null, $params = null, $description = 'description', $id = 'id')
    {
        $views = null;
        $array = array();

        if (!$params['selected']) {
            switch ($params['values']) {
                case 'single':
                    $views = '<option value="" selected disabled>--Select--</option>';
                    break;
                case 'multiple':
                    $views = '';
                    break;
            }
        }

        if ($obj) {
            foreach ($obj as $row) {
                $field = $row->$description;
                $id_field = $row->$id;
                switch ($params['type']) {
                    case 'select':
                        if (in_array($id_field, $params['selected'])) {
                            $views .= '<option value="' . $id_field . '"  selected>' . $field . '</option>';
                        } else {
                            $views .= '<option value="' . $id_field . '">' . $field . '</option>';
                        }
                        break;
                    case 'slinks':
                        $array[] = '<a href="' . $this->_link_home . '/' . $id_field . '">' . $field . '</a>';
                        break;
                    case 'llinks':
                        $views .= '<li><a href="' . $this->_link_home . '/' . $id_field . '">' . $field . '</a></li>';
                        break;
                    case 'header':
                        $views .= '<option value="' . $id_field . '">' . $field . '</option>';
                        break;
                }
            }
        } else {
            die('contact admin');
        }

        return $views ? $views : $array;
    }
}
