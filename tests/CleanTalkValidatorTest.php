<?php

/**
 * @coversDefaultClass CleanTalkValidator
 */
class CleanTalkValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setResponse($allow, $message)
    {
        $mock = $this->getMock('CleanTalkApi', array('sendRequest'));
        $mock->apiKey = CLEANTALK_TEST_API_KEY;
        $mock->init();
        $mock->expects($this->once())->method('sendRequest')->will(
            $this->returnValue(
                new CleantalkResponse(
                    array(
                        'allow' => $allow,
                        'comment' => $message,
                    )
                )
            )
        );

        Yii::app()->setComponent('cleanTalk', $mock, false);
    }

    public function testValidator()
    {
        $this->setResponse(0, 'Forbidden');

        $model = new FakeModel();
        $model->message = 'example1';
        $model->validate();
        $this->assertTrue($model->hasErrors('message'));
    }

    public function testValidatorAllow()
    {
        $this->setResponse(1, '');

        $model = new FakeModel();
        $model->message = 'example1';
        $model->validate();
        $this->assertFalse($model->hasErrors('message'));
    }

    /**
     * @expectedException CException
     */
    public function testCheckValidateConfig()
    {
        $class = new ReflectionClass('CleanTalkValidator');
        $method = $class->getMethod('checkValidateConfig');
        $method->setAccessible(true);
        $validator = new CleanTalkValidator();
        $validator->check = CleanTalkValidator::CHECK_MESSAGE;

        Yii::app()->setComponent('cleanTalk', null);
        $method->invoke($validator, new FakeModel());
    }

    /**
     * @expectedException DomainException
     */
    public function testCheckValidateConfigCheck()
    {
        $class = new ReflectionClass('CleanTalkValidator');
        $method = $class->getMethod('checkValidateConfig');
        $method->setAccessible(true);
        $validator = new CleanTalkValidator();
        $validator->check = 'unkwno checkl type';

        Yii::app()->setComponent('cleanTalk', 'CleanTalkApi');
        $method->invoke($validator, new FakeModel());
    }
}

class FakeModel extends CFormModel
{
    public $message;

    public function rules()
    {
        return array(
            array('message', 'CleanTalkValidator', 'check' => 'message')
        );
    }
}