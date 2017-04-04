<?php

namespace Medlib\Testing\Paginator;

use Tests\TestCase;
use Illuminate\Http\Request;
use Medlib\Yaz\Record\YazRecords;
use Medlib\Yaz\Pagination\Paginator;

class PaginatorTest extends TestCase
{
    public function testBuildPaginationClass()
    {
        $request = new Request([
            'page' => 1
        ]);
        $paginator = new Paginator('SUDOC', 10);

        $paginator->getQuery()
            ->where('au="totok"')
            ->orderBy('ti ASC');

        $paginator->setPage($request->get('page', 1));

        $paginator->init(YazRecords::TYPE_XML);

        $this->assertEquals($paginator->getNbResults(), 43);
    }
}
