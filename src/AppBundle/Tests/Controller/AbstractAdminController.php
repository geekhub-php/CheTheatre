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

    protected function processDeleteAction($object)
    {
        $this->logIn();

        $namespaceParts = explode('\\', get_class($object));
        $entityName = array_pop($namespaceParts);

        $page = $this->request(sprintf('/admin/%s/%s/delete?tl=en', $entityName, $object->getId()), 'GET', 200);
        $form = $this->getConfirmDeleteFormObject($page);

        $this->getClient()->followRedirects(true);
        $listPage = $this->getClient()->submit($form);

        $this->assertContains(
            sprintf('Item "%s" has been deleted successfully.', $object),
            trim($listPage->filter('.alert-success')->text())
        );

        // Tested softdeleteable and blameable
        $id = $object->getId();
        $this->getEm()->detach($object);
        $object = $this->getEm()->getRepository('AppBundle:'.$entityName)->find($id);
        $this->assertNull($object);

        $this->getEm()->getFilters()->disable('softdeleteable');
        $object = $this->getEm()->getRepository('AppBundle:'.$entityName)->find($id);
        $this->assertNotNull($object, sprintf('SoftDeleteable filter is not active for "%s" entity', $entityName));
        $this->assertEquals('admin', $object->getDeletedBy());

        $this->getEm()->getFilters()->enable('softdeleteable');
    }
}
