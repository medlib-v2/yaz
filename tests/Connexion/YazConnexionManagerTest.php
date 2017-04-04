<?php

namespace Medlib\Testing\Connexion;

use Mockery;
use Tests\TestCase;
use Medlib\Yaz\Query\YazQuery;
use Medlib\Yaz\Connexion\YazConnexion;

class YazConnexionManagerTest extends TestCase
{
    protected $instance, $manger;

    /**
     * @var \Mockery\MockInterface|\Medlib\Yaz\Connexion\YazConnexion
     */
    protected $mock;


    public function setUp()
    {
        parent::setUp();
        $this->getInstance();
    }

    /**
     * Set down the environment of test
     */
    public function tearDown()
    {
        $this->manger->shutdown();
        unset($this->instance);
        unset($this->manger);
        parent::tearDown();
    }

    public function testConnexionReturnInstanceOfYazConnexionManager()
    {
        $this->assertInstanceOf(YazConnexion::class, $this->manger);
    }

    private function getInstance()
    {
       $this->manger = new YazConnexion('SUDOC');
        $this->instance = $this->manger->getConnection();
    }

    public function testQueryBuilderAnsResource()
    {
        $response = YazQuery::create()
            ->from('SUDOC')
            ->where('au="totok" and ti="Handbuch"')
            ->orderBy('au ASC')
            ->limit(0, 3);

        $request = $response->getBaseRequest();

        $this->assertEquals($request['from'], 'SUDOC');
        $this->assertEquals($request['where'], 'au="totok" and ti="Handbuch"');
        $this->assertEquals($request['order'], 'au ASC');
        $this->assertEquals($request['limit']['start'], 1);
        $this->assertEquals($request['limit']['end'], 3);

        $this->assertEquals($request['rpn'], '@and @attr 1=1003 totok @attr 1=4 Handbuch');

        $result = $response->all();

        $this->assertEquals($result->getHits(), 24, 'Testing count of result');

    }
}