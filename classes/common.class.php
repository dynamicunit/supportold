<?php

class common
{
    // properties
    private $_db;
    private $_params = [];

    // methods
    public function __construct()
    {
        $this->_db = db::getinstanace();
        if (!$this->set_params()) {
            throw new Exception("Environment variables are not configured");
        }
    }

    private function set_params(): bool
    {
        $query = "SELECT * FROM configurations WHERE website_id = ?";
        $data = $this->_db->query($query, [config::get('website/website_code')]);

        if (!$data->error()) {
            foreach ($data->results() as $row) {
                $this->_params[$row->property] = $row->property_value;
            }
            return true;
        } else {
            return false;
        }
    }

    // Get value method
    public function get_value(string $property): string
    {
        if (empty($property)) {
            throw new InvalidArgumentException("Property value required");
        }

        if (array_key_exists($property, $this->_params)) {
            return $this->_params[$property];
        } else {
            throw new InvalidArgumentException("Property value invalid");
        }
    }
}
