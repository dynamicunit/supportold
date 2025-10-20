<?php

class users
{

    ///////////////////////////////////// variables for the user class ///////////////////////////

    protected $_db;

    // details of the logged user
    protected $_session_name;

    protected $_loggeduser;

    protected $_loggedin = false;

    protected $_query_count = 'select FOUND_ROWS() as total';

    protected $_profile_code = null;

    protected $_fetcheddata;

    /////////////////////////////////////// fucntions for the class ///////////////////////////////////////

    public function __construct($user = null)
    {
        $this->_db = db::getinstanace();

        $this->_session_name = config::get('session/session_name');

        if (!$user) {

            if (session::exists($this->_session_name)) {
                $user = session::get($this->_session_name);
                if ($this->find($user)) {
                    $this->_loggedin = true;
                } else {
                    $this->logout();
                }
            }
        } else {
            $this->find($user);
        }
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

    public function find($user = null)
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'id' : 'email';
            $data = $this->fetch('users', array($field => array('=', $user)));

            if ($this->get_count()) {
                $this->_loggeduser = $data;
                return true;
            }
        }
        return false;
    }

    public function logout()
    {
        session::delete($this->_session_name);
        $this->_loggeduser = null;
        $this->_loggedin = false;
        return true;
    }

    public function verify_password($password)
    {
        if ($this->_loggeduser->password === hash::make($password, $this->_loggeduser->salt)) {
            return true;
        } else {
            return false;
        }
    }

    public function login($email = null, $password = null)
    {
        if (($this->find($email)) && ($this->verify_password($password))) {
            if ($this->_loggeduser->is_email_verified) {
                session::put(config::get('session/session_name'), $this->_loggeduser->id);
                $this->_loggedin = true;
                $this->update_lastlogin();
                return 2;
            } else {
                return 1;
            }
        }
        return 0;
    }

    public function update_lastlogin()
    {
        $function = new functions();

        // update in the users table
        $fields = [
            'last_login' => date('Y-m-d H:i:s'),
            'last_login_ip_address' => $function->get_ip()
        ];

        $conditions = [
            'id' => ['=', $this->_loggeduser->id]
        ];

        $this->update('users', $fields, $conditions);

        //insert data into user_login_history

        $this->add('user_logins_history', [
            'user_id' => $this->_loggeduser->id,
            'login_date' => date('Y-m-d H:i:s'),
            'ip_address' => $function->get_ip()
        ]);
        return true;
    }

    public function send_email($email_template_name, $recipient_address = '', $values = [])
    {
        $recipient_email = $recipient_address ?: $this->_loggeduser->email;

        $email = new email(
            $email_template_name,
            $recipient_email,
            $values
        );

        return $email;
    }

    public function get_loggeduser()
    {
        return $this->_loggeduser;
    }

    public function get_loggedin()
    {
        return $this->_loggedin;
    }

    ////////////////// START  OF USER MESSAGE FUNCTIONS ////////////////////////////////

    public function get_user_messages($user_id = null)
    {
        $user_id = $user_id ?: $this->_loggeduser->id;

        $query = 'SELECT
                msgs.id,
                CASE 
                    WHEN msgs.sender_id = ? THEN msgs.receiver_id
                    ELSE msgs.sender_id
                END AS contact_id,
                LEFT(msgs.message_body, 200) AS message_body,
                msgs.is_read_by_receiver,
                msgs.created_at,
                users.id AS user_id,
                users.full_name AS username,
                users.slug,
                users.profile_image
            FROM
                user_messages msgs
            INNER JOIN (
                SELECT
                    CASE
                        WHEN sender_id = ? THEN receiver_id
                        ELSE sender_id
                    END AS contact_id,
                    MAX(created_at) AS latest_msg_date
                FROM
                    user_messages
                WHERE
                    (sender_id = ? AND is_deleted_by_sender = 0)
                    OR (receiver_id = ? AND is_deleted_by_receiver = 0)
                GROUP BY
                    contact_id
            ) latest_msgs
            ON
                (msgs.sender_id = latest_msgs.contact_id OR msgs.receiver_id = latest_msgs.contact_id)
                AND msgs.created_at = latest_msgs.latest_msg_date
            INNER JOIN users
            ON users.id = contact_id
            WHERE
                (msgs.sender_id = ? AND is_deleted_by_sender = 0)
                OR (msgs.receiver_id = ? AND is_deleted_by_receiver = 0)
            ORDER BY msgs.created_at DESC;';

        $params = array($user_id, $user_id, $user_id, $user_id, $user_id, $user_id);

        try {
            $temp = $this->_db->query($query, $params);
            if ($temp->count()) {
                return $temp->results();
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Handle exception
            return false;
        }
    }



    public function get_user_list($user_id)
    {
        $query = "
        SELECT 
            CASE 
                WHEN ui.sender_id = ? THEN ureceiver.id 
                ELSE usender.id 
            END AS user_id,
            CASE 
                WHEN ui.sender_id = ? THEN ureceiver.full_name 
                ELSE usender.full_name 
            END AS full_name,
            CASE 
                WHEN ui.sender_id = ? THEN ureceiver.email 
                ELSE usender.email 
            END AS email,
            CASE 
                WHEN ui.sender_id = ? THEN ureceiver.profile_image 
                ELSE usender.profile_image 
            END AS profile_image
        FROM 
            user_interests ui
        LEFT JOIN 
            users usender ON ui.sender_id = usender.id
        LEFT JOIN 
            users ureceiver ON ui.receiver_id = ureceiver.id
        WHERE 
            (ui.sender_id = ? OR ui.receiver_id = ?)
            AND ui.is_accepted_by_receiver = 1
    ";
        $params = array($user_id, $user_id, $user_id, $user_id, $user_id, $user_id);

        try {
            $temp = $this->_db->query($query, $params);
            if ($temp->count()) {
                return $temp->results();
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Handle exception
            return false;
        }
    }


    public function get_conversation($user = null)
    {
        $myuser = $this->_loggeduser->id;
        $query = 'SELECT
                id,
                sender_id,
                receiver_id,
                message_body,
                created_at
            FROM
                user_messages
            WHERE
                (sender_id = ? AND receiver_id = ? AND is_deleted_by_sender = 0)
                OR
                (sender_id = ? AND receiver_id = ? AND is_deleted_by_receiver = 0)
            ORDER BY
                created_at ASC;';

        $temp = $this->_db->query($query, array(
            $myuser,
            $user,
            $user,
            $myuser
        ));

        if ($temp->count()) {
            return $temp->results();
        } else {
            return false;
        }
    }

    public function delete_messages($user = null)
    {
        $myuser = $this->_loggeduser->id;
        $deleteflag = 1;
        $this->_db->query('update user_messages set is_deleted_by_sender = 1 where sender_id = ? and receiver_id = ? and website_id = ?', array(
            $myuser,
            $user,
            config::get('website/website_code')
        ));

        $this->_db->query('update user_messages set is_deleted_by_receiver = 1 where sender_id = ? and receiver_id = ? and website_id = ?', array(
            $user,
            $myuser,
            config::get('website/website_code')
        ));

        $this->_db->query('delete from user_messages where is_deleted_by_sender = ? and is_deleted_by_receiver = ? and website_id = ?', array(
            $deleteflag,
            $deleteflag,
            config::get('website/website_code')
        ));
    }


    ////////////////// END OF USER MESSAGE FUNCTIONS ////////////////////////////////

}
