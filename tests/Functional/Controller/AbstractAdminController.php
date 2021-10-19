<?php

namespace App\Tests\Functional\Controller;

use Symfony\Component\DomCrawler\Crawler;

class AbstractAdminController extends AbstractController
{
    protected function assertAdminListPageHasColumns(array $columns)
    {
        $expectedColumns = $columns;
        $page = $this->getSessionClient()->getCrawler();

        $listPageColumns = $page->filter('.sonata-ba-list-field-header')->children()->each(function (Crawler $column, $i) {
            if (false == strpos($column->attr('class'), 'sonata-ba-list-field-header-batch')) {
                return trim($column->text());
            }
        });
        $listPageColumns = array_filter($listPageColumns);

        $notHaveColumns = array_diff($expectedColumns, $listPageColumns);
        $this->assertEquals([], $notHaveColumns, sprintf(
            'Not have columns "%s" in "%s"',
            implode(', ', $notHaveColumns),
            implode(', ', $listPageColumns)
        ));

        $extraColumns = array_diff($listPageColumns, $expectedColumns);
        $this->assertEquals([], $extraColumns, sprintf('Found extra columns "%s"', implode(', ', $extraColumns)));
    }

    protected function processDeleteAction($object)
    {
        $this->logIn();

        $objectNS = get_class($object);
        $namespaceParts = explode('\\', $objectNS);
        $entityName = array_pop($namespaceParts);

        $page = $this->request(sprintf('/admin/%s/%s/delete?tl=en', $entityName, $object->getId()), 'GET', 200);
//        file_put_contents('/tmp/test.html', $this->getSessionClient()->getResponse()->getContent());
        $form = $this->getConfirmDeleteFormObject($page);

        $this->getSessionClient()->followRedirects(true);
        $listPage = $this->getSessionClient()->submit($form);

        $this->assertNotFalse(
            strpos(
                trim($listPage->filter('.alert-success')->text()),
                'has been deleted successfully.'
            )
        );

        // Tested softdeleteable and blameable
        $id = $object->getId();
        $this->getEm()->detach($object);
        $object = $this->getEm()->find($objectNS, $id);
        $this->assertNull($object);

        $this->getEm()->getFilters()->disable('softdeleteable');
        $object = $this->getEm()->find($objectNS, $id);
        $this->assertNotNull($object, sprintf('SoftDeleteable filter is not active for "%s" entity', $entityName));
        $this->assertEquals('admin', $object->getDeletedBy());

        $this->getEm()->getFilters()->enable('softdeleteable');
    }
}
