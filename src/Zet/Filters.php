<?php


namespace Zet;


class Filters
{
    private $frameworkFilters = array(
        'filterujem'
    );

    public function registerFrameworkFilters($latte)
    {
        $filter = $this;
        foreach ($this->frameworkFilters as $filterName) {
            $latte->addFilter($filterName, function($string) use($filter, $filterName) {
                return $filter->$filterName($string);
            });
        }
    }

    public function filterujem($string)
    {
        return ucfirst($string);
    }


}