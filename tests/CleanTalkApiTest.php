<?php

/**
 * @coversDefaultClass CleanTalkApi
 */
class CleanTalkApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CleanTalkApi
     */
    protected $component;

    protected function setUp()
    {
        $this->component = Yii::createComponent(array('class' => 'CleanTalkApi', 'apiKey' => CLEANTALK_TEST_API_KEY));
        $this->component->init();
    }

    /**
     * @expectedException CException
     */
    public function testInit()
    {
        $component = Yii::createComponent(array('class' => 'CleanTalkApi', 'apiKey' => null));
        $component->init();
    }

    public function testIsAllowUser()
    {
        $mock = $this->getSendRequestMock(
            array(
                'allow' => 1,
                'comment' => ''
            )
        );
        $result = $mock->isAllowUser('allow@email.ru', 'Ivan Petrov');
        $this->assertTrue($result);

        $mock = $this->getSendRequestMock(
            array(
                'allow' => 0,
                'comment' => 'Mock deny'
            )
        );
        $result = $mock->isAllowUser('deny@email.ru', 'Ivan Petrov');
        $this->assertFalse($result);
        $this->assertEquals('Mock deny', $mock->getValidationError());

        $mock = $this->getSendRequestMock(
            array(
                'inactive' => 1,
                'comment' => 'Mock deny'
            )
        );
        $result = $mock->isAllowUser('deny@email.ru', 'Ivan Petrov');
        $this->assertFalse($result);
    }

    public function testIsAllowMessage()
    {
        $mock = $this->getSendRequestMock(
            array(
                'allow' => 1,
                'comment' => ''
            )
        );

        $result = $mock->isAllowMessage('message1');
        $this->assertTrue($result);

        $mock = $this->getSendRequestMock(
            array(
                'allow' => 0,
                'comment' => 'Mock deny'
            )
        );
        $result = $mock->isAllowMessage('bad message');
        $this->assertFalse($result);
        $this->assertEquals('Mock deny', $mock->getValidationError());
    }

    public function testGetCheckJsCode()
    {
        $this->assertRegExp('#\w+#', $this->component->getCheckJsCode());
    }

    public function testGetValidationError()
    {

    }

    public function testStartFormSubmitTime()
    {
        $this->component->startFormSubmitTime();
    }

    public function testCheckJsHiddenField()
    {
        $string = $this->component->checkJsHiddenField();
        $this->assertStringStartsWith('<input', $string);
        $this->assertContains('"ct_checkjs"', $string);
        $this->assertTrue(Yii::app()->clientScript->isScriptRegistered('cleantalk_javascript', CClientScript::POS_END));
    }

    public function testGetFormSubmitTime()
    {
        $this->component->startFormSubmitTime();
        sleep(1);
        $submitTime = $this->component->getFormSubmitTime();
        $this->assertEquals(1, $submitTime);
    }

    public function testGetIsJavascriptEnable()
    {
        $class = new ReflectionClass($this->component);
        $method = $class->getMethod('getIsJavascriptEnable');
        $method->setAccessible(true);

        $_POST['ct_checkjs'] = $this->component->getCheckJsCode();

        $this->assertEquals(1, $method->invoke($this->component));

        unset($_POST['ct_checkjs']);
        $this->assertEquals(0, $method->invoke($this->component));
    }

    public function testCreateRequest()
    {
        $class = new ReflectionClass($this->component);
        $method = $class->getMethod('createRequest');
        $method->setAccessible(true);
        $request = $method->invoke($this->component);
        $this->assertInstanceOf('CleantalkRequest', $request);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSendRequest()
    {
        $class = new ReflectionClass($this->component);
        $method = $class->getMethod('sendRequest');
        $method->setAccessible(true);
        $method->invoke($this->component, new CleantalkRequest(), 'ololo');
    }

    protected function getSendRequestMock($response)
    {
        $mock = $this->getMock('CleanTalkApi', array('sendRequest'));
        $mock->expects($this->once())->method('sendRequest')->will(
            $this->returnValue(
                new CleantalkResponse(
                    $response
                )
            )
        );
        return $mock;
    }
}