<?php

class blog
{

    private $_db;

    protected $_fetcheddata;
    //logical name  => actual table name


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

    public function get_blogpost($blog_slug = null)
    {
        $query = 'SELECT 
                        a.*,  
                        c.full_name, 
                        c.profile_image 
                    FROM blogs AS a
                    LEFT JOIN users AS c 
                        ON a.user_id = c.id
                    WHERE a.slug = ?;
                    ';

        $temp = $this->_db->query($query, array(
            $blog_slug
        ));

        if ($temp->count()) {
            return $temp->first();
        } else {
            return false;
        }
    }


    public function get_blogslist($data)
    {
        $random_posts_html = '<div class="blogs-container">'; // Start the flex container

        foreach ($data as $post) {
            // Ensure all required fields have fallback values
            $title = isset($post['blog_title']) ? htmlspecialchars($post['blog_title']) : 'Untitled';
            $image = isset($post['image']) ? htmlspecialchars($post['image']) : 'img/default-image.jpg';
            $slug = isset($post['slug']) ? htmlspecialchars($post['slug']) : '#';
            $description = isset($post['meta_desc']) ? htmlspecialchars($post['meta_desc']) : 'No description available';
            $category = isset($post['description']) ? htmlspecialchars($post['description']) : 'General';
            $createdAt = isset($post['created_at']) ? date("F j, Y", strtotime(htmlspecialchars($post['created_at']))) : 'Unknown date';

            // Build the post URL
            $postUrl = config::get('website/website_url') . '/blog-post/' . urlencode($slug);

            // Generate the HTML for each blog card
            $random_posts_html .= <<<HTML
        <div class="blog-card">
            <a href="$postUrl">
                <img src="$image" alt="$title" class="blog-img">
            </a>
            <div class="blog-content">
                <h3><a href="$postUrl" class="text-dark text-decoration-none">$title</a></h3>
                <small class="text-muted d-block mb-2">$createdAt</small>
                <p class="text-muted mb-0">{$this->truncate_text($description, 80)}</p>
                <a href="$postUrl" class="read-more">Read more</a>
            </div>
        </div>
HTML;
        }

        $random_posts_html .= '</div>'; // Close the flex container

        return $random_posts_html;
    }

    /**
     * Truncate text to a specified length without cutting words
     */
    private function truncate_text($text, $length)
    {
        if (strlen($text) <= $length)
            return $text;
        return substr($text, 0, strpos(wordwrap($text, $length), "\n")) . '...';
    }
}
