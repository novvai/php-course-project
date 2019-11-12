<?php

namespace Novvai\Model\Utilities;

use Novvai\Model\Base as Model;

class Pagination
{

    /**
     * @var Model
     */
    private $modelInstance;

    /**
     * @var int $currentPage
     */
    private $currentPage;

    /**
     * @var int $limit
     */
    private $limit;

    public function __construct(Model $model, $currentPage, $limit)
    {
        $this->modelInstance = $model;
        $this->currentPage = $currentPage ?? 1;
        $this->limit = $limit;
        $this->handle();
    }

    private function handle()
    {
        $offset = ($this->currentPage * $this->limit) - $this->limit;
        $this->modelInstance->paginate($offset, $this->limit);
        $this->maxPages = ceil(($this->modelInstance->count() / 10));
    }

    public function render()
    {
        if($this->maxPages <= 1){
            echo "";
            return;
        }
        list($prevPage, $nextPage) = $this->generateControls();
        $pageContainerStart = "<ul class=\"pagination pagination-sm m-0 float-left\">$prevPage";
        $pageContainerEnd = "$nextPage</ul>";

        for ($i = 1; $i <= $this->maxPages; $i++) {
            $activeClass = ($this->currentPage == $i) ? "active" : "";
            $pageContainerStart .= "<li class=\"page-item $activeClass\"><a class=\"page-link\" href=\"{$this->generateUrl($i)}\">{$i}</a></li>";
        }

        echo $pageContainerStart . $pageContainerEnd;
    }

    public function __call($name, $arguments)
    {
        return $this->modelInstance->{$name}(...$arguments);
    }

    private function generateUrl($pageNumber)
    {
        $query = $_SERVER['QUERY_STRING'];

        if (strlen($query) === 0) {
            return "?page={$pageNumber}";
        }

        if (strpos($query, "page=") !== false) {
            return "?" . preg_replace("/page=\d*/", "page={$pageNumber}", $query);
        }
        return "?" . $query . "&page={$pageNumber}";
    }

    private function generateControls()
    {
        $prevPage = '<li class="page-item "><a class="page-link" href="' . $this->generateUrl($this->currentPage - 1) . '">«</a></li>';
        $nexPage = '<li class="page-item "><a class="page-link" href="' . $this->generateUrl($this->currentPage + 1) . '">»</a></li>';
        if (($this->currentPage - 1 == 0)) {
            $prevPage = '<li class="page-item disabled"><a class="page-link" href="#">«</a></li>';
        }
        if ($this->maxPages < $this->currentPage + 1) {
            $nexPage = '<li class="page-item disabled"><a class="page-link" href="">»</a></li>';
        }
        return [$prevPage, $nexPage];
    }
}
