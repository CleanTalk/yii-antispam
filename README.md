##Yii-antispam
Anti-spam by CleanTalk extension with protection against spam bots and manual spam.

No Captcha, no questions, no counting animals, no puzzles, no math.

##Requirements

Yii 1.1 or above

##Usage

1. Get access key on http://cleantalk.org/register?platform=yii
2. Extract content from archive under protected/extensions/yii-antispam 
3. Open your application configuration in protected/config/main.php and modify components section:
~~~
// application components
'components'=>array(
    ...
        'cleanTalk'=>array(
            'class'=>'ext.yii-antispam.CleanTalkApi',
            'apiKey'=>'*****',
        ),
    ...
),
~~~
4. Add validator in your model, for example ContactForm
~~~
class ContactForm extends CFormModel
{
    public $name;
    public $email;
    public $body;
    ...
    public function rules()
    {
        return array(
            ...
            array('body', 
                    'ext.yii-antispam.CleanTalkValidator', 
                    'check'=>'message', /* Check type message or user */
                    'emailAttribute'=>'email',  
                    'nickNameAttribute'=>'name'),
            ...
        );
    }
    ...
}
~~~

##License
GNU General Public License

##Resources

 * http://cleantalk.org/
 * https://github.com/CleanTalk/yii-antispam

