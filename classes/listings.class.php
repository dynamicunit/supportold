<?php

class listings
{
    protected $_db;

    protected $_fetcheddata;


    public function __construct()
    {
        $this->_db = db::getinstanace();
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

    public function search_listings($params)
    {
        $query = 'SELECT SQL_CALC_FOUND_ROWS  
            a.website_id,
            a.id,
            a.slug,
            a.user_id,
            a.title,
            a.profile_image,
            a.address,
            a.jurisdiction, 
            a.categories,
            SUBSTRING(a.profile_description, 1, 300) as profile_description,
            b.country_description, 
            b.state_description, 
            b.city_description
        FROM listings a
        LEFT JOIN materialized_location as b ON a.city_id = b.city_id
        WHERE a.website_id = ? AND a.is_active = 1';

        $queryParams = [config::get('website/website_code')];
        $conditions = [];

        if (!empty($params['location'])) {
            $conditions[] = 'a.city_id = ?';
            $queryParams[] = $params['location'];
        }

        if (!empty($params['category'])) {
            $conditions[] = 'a.id IN (SELECT listing_id FROM rel_listing_category WHERE category_id = ?)';
            $queryParams[] = $params['category'];
        }

        if (!empty($conditions)) {
            $query .= ' AND ' . implode(' AND ', $conditions);
        }

        // Add sorting
        if (!empty($params['orderby'])) {
            $query .= ' ORDER BY ' . $params['orderby'] . ' ' . $params['asc_desc'];
        } else {
            $query .= ' ORDER BY a.created_at desc';
        }

        // Add limit
        if (!empty($params['limit'])) {
            $query .= ' LIMIT ' . $params['limit'];
        }

        // Add offset
        if (!empty($params['offset'])) {
            $query .= ' offset ' . $params['offset'];
        }

        //die ($query);
        $data = $this->_db->query($query, $queryParams);

        if ($data->count()) {
            $this->_fetcheddata = $data->results();
        } else {
            return false;
        }
    }

    public function get_listing($listing_id)
    {
        $query = 'SELECT 
            a.*,
            b.country_description, 
            b.state_description, 
            b.city_description, 
            b.country_id, 
            b.state_id, 
            b.city_id, 
            c.categories
        FROM listings a
        LEFT JOIN materialized_location as b ON a.city_id = b.city_id
        LEFT JOIN profile_listing_categories as c on a.id = c.listing_id
        WHERE a.website_id = ? AND a.id = ? AND a.is_active = 1 limit 1';

        $data = $this->_db->query($query, [config::get('website/website_code'), $listing_id]);

        if ($data->count() == 1) {
            $this->_fetcheddata = $data->first();
            return true;
        } else {
            return false;
        }
    }

    public function get_categorylist($list)
    {
        if (empty($list)) {
            return '';
        }

        $placeholders = implode(',', array_fill(0, count($list), '?'));

        $query = "SELECT description FROM listing_categories WHERE id IN ($placeholders)";

        $data = $this->_db->query($query, $list);

        if ($data->count() > 0) {
            $categoryNames = [];
            foreach ($data->results() as $row) {
                $categoryNames[] = $row->description;
            }
            return implode(', ', $categoryNames);
        } else {
            return '';
        }
    }


    public function get_statuslist_by_group($groupId, $fromStatusId = null)
{
    if (empty($groupId)) {
        return [];
    }

    // Default null to 0
    if ($fromStatusId === null) {
        $fromStatusId = 0;
    }

    $query = "
        SELECT 
            ts.status_id,
            ts.status_name
        FROM status_authorizations sa
        INNER JOIN ticket_statuses ts 
            ON sa.to_status_id = ts.status_id
        WHERE sa.group_id = ?
          AND sa.from_status_id = ?
          AND ts.is_active = 1
        ORDER BY ts.status_id ASC
    ";

    $data = $this->_db->query($query, [$groupId, $fromStatusId]);

    if ($data->count() > 0) {
        $statusList = [];
        foreach ($data->results() as $row) {
            $statusList[$row->status_id] = $row->status_name;
        }
        return $statusList;
    } else {
        return [];
    }
}


    public function get_currentstatus($StatusId = null)
{
    

    $query = "
        SELECT status_name from ticket_statuses WHERE status_id = ?
          AND is_active = 1 
            
    ";

    $data = $this->_db->query($query, [$StatusId]);

    if ($data->count() > 0) {
       return $data->first()->status_name;
    } else {
        return null;
    }
}


}
