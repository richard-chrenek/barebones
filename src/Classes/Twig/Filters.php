<?php

namespace Barebones\Classes\Twig;

use Barebones\Helpers\DateHelper;
use DateTime;
use Twig\Environment;
use Twig\TwigFilter;

class Filters
{
    /** @var Environment */
    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->addIsTodayFilter()
            ->addIsYesterdayFilter();
    }


    protected function addIsTodayFilter()
    {
        $isTodayFilter = new TwigFilter('isToday', function (DateTime $dateTime) {
            return DateHelper::isToday($dateTime);
        });

        $this->twig->addFilter($isTodayFilter);
        return $this;
    }

    protected function addIsYesterdayFilter()
    {
        $isYesterdayFilter = new TwigFilter('isYesterday', function (DateTime $dateTime) {
            return DateHelper::isYesterday($dateTime);
        });

        $this->twig->addFilter($isYesterdayFilter);
        return $this;
    }
}
