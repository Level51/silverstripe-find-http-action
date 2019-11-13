<?php

namespace Level51\FindHTTPAction;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Dev\TestOnly;

class FindHttpActionTest extends SapphireTest {

    protected function setUp() {
        parent::setUp();

        Config::modify()->set(Director::class, 'rules', [
            'testfindhttpaction' => FindHttpActionTestController::class
        ]);

        Config::modify()->set(FindHttpActionTestController::class, 'url_handlers', [
            ''                     => [
                'GET'    => 'testGetAction',
                'POST'   => 'testPostAction',
                'PUT'    => 'testPutAction',
                'DELETE' => 'testDeleteAction'
            ],
            'testnormal'           => 'testNormal',
            'testdynamic/$testVar' => [
                'PUT' => 'testDynamicPut'
            ]
        ]);
    }

    public function testFindGETAction() {
        $response = Director::test('testfindhttpaction');

        $this->assertEquals('get', $response->getBody());
    }

    public function testFindPOSTAction() {
        $response = Director::test('testfindhttpaction', [], [], 'POST');

        $this->assertEquals('post', $response->getBody());
    }

    public function testFindPUTAction() {
        $response = Director::test('testfindhttpaction', [], [], 'PUT');

        $this->assertEquals('put', $response->getBody());
    }

    public function testFindDELETEAction() {
        $response = Director::test('testfindhttpaction', [], [], 'DELETE');

        $this->assertEquals('delete', $response->getBody());
    }

    public function testFindDynamicPUTAction() {
        $response = Director::test('testfindhttpaction/testdynamic/helloworld', [], [], 'PUT');

        $this->assertEquals('helloworld', $response->getBody());
    }

    public function testNormal() {
        $response = Director::test('testfindhttpaction/testnormal');

        $this->assertEquals('normal', $response->getBody());
    }
}

class FindHttpActionTestController extends Controller implements TestOnly {

    use FindHTTPAction;

    public function testGetAction() {
        return 'get';
    }

    public function testPostAction() {
        return 'post';
    }

    public function testPutAction() {
        return 'put';
    }

    public function testDeleteAction() {
        return 'delete';
    }

    public function testDynamicPut(HTTPRequest $request) {
        return $request->param('testVar');
    }

    public function testNormal() {
        return 'normal';
    }

    public function checkAccessAction($action) {
        return true;
    }
}
