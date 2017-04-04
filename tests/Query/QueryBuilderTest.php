<?php

namespace  Medlib\Testing\Query;

use Tests\TestCase;
use Illuminate\Http\Request;
use Medlib\Yaz\Parser\ParseQuery;
use Medlib\Yaz\Parser\SimpleQuery;
use Medlib\Yaz\Parser\AdvancedQuery;

class QueryBuilderTest extends TestCase
{
    protected $instance;

    /**
     * Set up the environment of test
     */
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
        unset($this->instance);
        parent::tearDown();
    }

    public function testConnexionReturnInstanceOfSimpleQuery()
    {
        $request = new Request([]);
        $builder = $this->instance->simple($request);
        $this->assertInstanceOf(SimpleQuery::class, $builder);
    }

    public function testConnexionReturnInstanceOfAdvancedQuery()
    {
        $request = new Request([]);
        $builder = $this->instance->advanced($request);
        $this->assertInstanceOf(AdvancedQuery::class, $builder);
    }

    public function testSimpleQueryBuilderWithTitleParams()
    {
        $request = new Request([
            "query" => "Victor Hugo",
            "qdb" => "BNF",
            "title" => "ti",
            "type" => "books"
        ]);

        $query = $this->builderQuery('simple', $request)->get();
        $this->assertEquals('ti="Victor Hugo"', $query);
    }

    /**
     * @test builder simple query
     */
    public function testSimpleQueryBuilderWithAllOptions()
    {
        $request = new Request([
            "query" => "Victor Hugo",
            "qdb" => "BNF",
            "title" => "ti",
            "author" => "au",
            "publisher" => "pb",
            "uniforme" => "ut",
            "dofpublisher" => "yr",
            "keywords" => "kw",
            "abstract" => "nt",
            "type" => "all"
        ]);

        $query = $this->builderQuery('simple', $request)->get();

        $this->assertEquals('ti="Victor Hugo" or au="Victor Hugo" or pb="Victor Hugo" or ut="Victor Hugo" or kw="Victor Hugo"', $query);
    }

    /**
     * @test builder advanced query
     */
    public function testAdvancedQueryBuilderWithTitleParams()
    {
        $request = new Request([
            "words" => [
                0 => [
                    "condition" => "-1",
                    "title" => "ti",
                    "query" => "Les misérables",
                ],
                1 => [
                    "condition" => "or",
                    "title" => "au",
                    "query" => "Victor Hugo",
                ]
            ],
            "qdb" => "BNF",
            "language" => "fr",
            "start" => 1,
            "limit" => 10,
            "type" => "all"
        ]);

        $query = $this->builderQuery('advanced', $request)->get();

        $this->assertEquals('ti="Les misérables" or au="Victor Hugo" and ln="fr"', $query);
    }

    /**
     * @param string $method
     * @param Request $request
     * @return mixed
     */
    private function builderQuery($method, Request $request) {
        $builder =  $this->instance->{$method}($request);
        return $builder;
    }

    /**
     * Set the default instance should be used in this test
     * @return void
     */
    private function getInstance()
    {
        $this->instance = new ParseQuery();
    }
}
