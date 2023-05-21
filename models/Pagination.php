<?php

namespace App;
use App\Driver;
use App\Panne;

class Pagination extends Driver {
    private $nbResultsPerPage;
    private $redirection;
    private $currentPage;
    private $searching;

    private $all_results = [];
    private $cut_results = [];
    private $results;
    private $totalResults;

    private $nbPagination;

    private $variables = [];
    private $params = [];

    public function __construct(int $nbResultsPerPage = 5) {
        $this->nbResultsPerPage = $nbResultsPerPage;
        $this->currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $this->searching = false;
    }

    function getDebut(): int
    {
        return ($this->currentPage - 1) * $this->nbResultsPerPage;
    }

    function getNbResultsPerPage(): int
    {
        return $this->nbResultsPerPage;
    }
    

    function setRedirection(string $redirection): void
    {
        $this->redirection = $redirection;
    }

    function setVariablesSearch(array $variables): void
    {
        foreach($variables as $variable => $position) {
            if (isset($_GET[$variable])) {
                $value = htmlentities($_GET[$variable]);
                switch ($position) {
                    case 'debut':
                        $value = '%' . $value;
                    break;

                    case 'deux':
                        $value = '%' . $value . '%';
                    break;

                    case 'fin':
                        $value = $value . '%';
                    break;
                }
                $this->variables[$variable] = $value;
            }
        }
    }

    function getValeurs()
    {
        return $this->variables;
    }

    function getLaValeurVariable(string $laVariable)
    {
        return isset($this->variables[$laVariable]) ? $this->variables[$laVariable] : '';
    }
    
    function getParams(): array
    {
        return $this->params;
    }

    function setParams(array $variables): void
    {
        foreach($variables as $column => $value) {
            $this->params[$column] = $value;
        }
    }
    
    function setLesResults(array $results_avec_pagination, array $results_sans_pagination)
    {
        $this->all_results[] = $results_sans_pagination;
        $this->cut_results[] = $results_avec_pagination;
    }

    function getCutResults(): array
    {
        return $this->cut_results;
    }

    function getAllResults(): array
    {
        return $this->all_results;
    }

    function getTotal(): int
    {
        return count($this->getAllResults());
    }

    function hasResult(): bool
    {
        return $this->currentPage <= $this->nbPagination;
    }

    /* public function render()
    {
        $nbPages = ceil($this->totalResults / $this->nbResultsPerPage);

        // afficher les résultats de la recherche
        if ($this->searching) {
            echo '<p>Résultats de la recherche pour : ' . $this->searchParams['num_série'] . ' ' . $this->searchParams['id_event'] . '</p>';
        }
    } */

    /* public function search() {
        $this->currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($this->currentPage <= 0) {
            header('Location:' . $this->redirection);
            exit();
        }
        $offset = ($this->currentPage - 1) * $this->nbResultsPerPage;
        $this->results = Panne::getLesPannes($this->searchParams, false, [$offset, $this->nbResultsPerPage]);
        $this->totalResults = count(Panne::getLesPannes($this->searchParams, false));
        $this->searching = !empty($_GET);
    } */
}