<?php

class pages
{

    private $_db;
    private $_meta_desc,
        $_meta_keywords,
        $_meta_title,
        $_page_title,
        $_page_title_desc,
        $_page_contents,
        $_page_code,
        $_page_slug;

       
    
    // methods
    public function __construct(
        ?string $page_code = null,
        ?string $meta_desc = null,
        ?string $meta_keywords = null,
        ?string $meta_title = null,
        ?string $page_title = null,
        ?string $page_title_desc = null,
        ?string $page_contents = null
    ) {
        $this->_db = db::getinstanace();

        $this->_page_code = $page_code;
        $this->_meta_desc = $meta_desc;
        $this->_meta_keywords = $meta_keywords;
        $this->_meta_title = $meta_title;
        $this->_page_contents = $page_contents;
        $this->_page_title = $page_title;
        $this->_page_title_desc = $page_title_desc;

        !$this->get_page() ? die("ERROR: Page can't be displyed. Contact website administrator.") : '';
    }

    private function get_page(): bool
    {
        if ($this->_page_code) {
            $field = (is_numeric($this->_page_code)) ? 'id' : 'slug';
        }

        $conditions = [
            $field => ['=', $this->_page_code],
            'website_id' => ['=', config::get('website/website_code')]
        ];

        $get = $this->_db->get('pages', $conditions, 1);

        if ($get->count()) {
            $page_line = $this->_db->first();

            $this->_meta_title = $this->_meta_title ?? $page_line->meta_title;
            $this->_meta_desc = $this->_meta_desc ?? $page_line->meta_description;
            $this->_meta_keywords = $this->_meta_keywords ?? $page_line->meta_keywords;

            $this->_page_title = $this->_page_title ?? $page_line->page_title;
            $this->_page_title_desc = $this->_page_title_desc ?? $page_line->page_title_description;
            $this->_page_contents = $this->_page_contents ?? $page_line->page_body;

            $this->_page_slug = $page_line->slug;

            return true;
        } else {
            return false;
        }
    }

    // Getter methods
    public function get_meta_desc(): ?string
    {
        return $this->_meta_desc;
    }

    public function get_meta_keywords(): ?string
    {
        return $this->_meta_keywords;
    }

    public function get_meta_title(): ?string
    {
        return $this->_meta_title;
    }

    public function get_page_contents(): ?string
    {
        return $this->_page_contents;
    }

    public function get_page_title(): ?string
    {
        return $this->_page_title;
    }

    public function get_page_title_desc(): ?string
    {
        return $this->_page_title_desc;
    }

    public function get_page_slug(): ?string
    {
        return $this->_page_slug;
    }



}
