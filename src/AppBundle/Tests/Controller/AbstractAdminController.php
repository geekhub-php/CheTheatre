<?php

namespace AppBundle\Tests\Controller;

use Symfony\Component\DomCrawler\Crawler;

class AbstractAdminController extends AbstractController
{
    protected function assertAdminListPageHasColumns(array $columns)
    {
        $expectedColumns = $columns;
        $page = $this->getClient()->getCrawler();

        $listPageColumns = $page->filter('.sonata-ba-list-field-header')->children()->each(function(Crawler $column, $i) {
            if (false == strpos($column->attr('class'), 'sonata-ba-list-field-header-batch')) {
                return trim($column->text());
            }
        });
        $listPageColumns = array_filter($listPageColumns);

        $notHaveColumns = array_diff($expectedColumns, $listPageColumns);
        $this->assertEquals([], $notHaveColumns, sprintf('Not have columns "%s"', implode(', ', $notHaveColumns)));

        $extraColumns = array_diff($listPageColumns, $expectedColumns);
        $this->assertEquals([], $extraColumns, sprintf('Found extra columns "%s"', implode(', ', $extraColumns)));
    }
}
