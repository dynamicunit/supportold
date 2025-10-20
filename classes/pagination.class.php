<?php

class Pagination
{
    private $totalItems;
    private $itemsPerPage=16;
    private $current;
    private $urlAppend;
    private $querystring;

    public function __construct($params = null)
    {
          $this->setTotalItems($params['total_records'] ?? 0);
        $this->setItemsPerPage($params['per_page'] ?? $this->itemsPerPage);
        $this->setCurrentPage($params['current_page_no'] ?? 1);
        $this->setUrlAppend($params['url_append'] ?? '/');
        $this->setQueryString($params['query_string'] ?? '');
    }

    public function setUrlAppend($append)
    {
        $this->urlAppend = $append;
    }

    public function setQueryString($querystring)
    {
        $this->querystring = $querystring;
    }

    public function setCurrentPage($current)
    {
        if ($current >= 0) {
            $this->current = $current;
        }
    }

    public function setItemsPerPage($items)
    {
        if ($items > 0) {
            $this->itemsPerPage = $items;
        }
    }

    public function setTotalItems($items)
    {
        if ($items > 0) {
            $this->totalItems = $items;
        }
    }

    private function buildUrl($pageNumber)
    {
        $url = $this->urlAppend;
        $queryString = $this->querystring;

        // Remove existing page parameter from the query string
        parse_str($queryString, $queryArray);
        unset($queryArray['page']);

        if ($pageNumber > 1) {
            $queryArray['page'] = $pageNumber;
        }

        $newQueryString = http_build_query($queryArray);

        if (!empty($newQueryString)) {
            $url .= '?' . $newQueryString;
        }

        return $url;
    }

    public function pagination()
    {
        $pageCount = ceil($this->totalItems / $this->itemsPerPage);
        
        if ($this->current >= 1 && $this->current <= $pageCount) {
            $current_range = [
                max(1, $this->current - 2),
                min($pageCount, $this->current + 2)
            ];

            $first_page = $this->current > 5 ? '<li class="page-item"><a class="page-link" href="' . $this->buildUrl(1) . '">1</a></li>' . ($this->current > 5 ? '<li class="page-item"><a class="page-link" href="#!" disabled>...</a></li>' : '') : '';

            $last_page = $this->current < $pageCount - 2 ? ($this->current < $pageCount - 4 ? '<li class="page-item"><a class="page-link" href="#!" disabled>...</a></li>' : '') . '<li class="page-item"><a class="page-link" href="' . $this->buildUrl($pageCount) . '">' . $pageCount . '</a></li>' : '';

            $previous_page = $this->current > 1 ? '<li class="page-item"><a class="page-link" href="' . $this->buildUrl($this->current - 1) . '">Previous</a></li>' : '';

            $next_page = $this->current < $pageCount ? '<li class="page-item"><a class="page-link" href="' . $this->buildUrl($this->current + 1) . '">Next</a></li>' : '';

            $pages = [];
            for ($x = $current_range[0]; $x <= $current_range[1]; $x++) {
                $pages[] = '<li class="page-item ' . ($this->current == $x ? 'active' : '') . '"><a class="page-link" href="' . $this->buildUrl($x) . '">' . $x . '</a></li>';
            }

            if ($pageCount > 1) {
                return '<ul class="pagination"> ' . $previous_page . $first_page . implode(' ', $pages) . $last_page . $next_page . '</ul>';
            }
        }
        return '';
    }
}
?>
