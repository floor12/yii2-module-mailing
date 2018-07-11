<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 10.05.2017
 * Time: 12:54
 */

namespace floor12\mailing\logic;


use app\models\mailing\Mailing;
use yii\base\ErrorException;

/**
 * Class MailQueue
 * @package app\logic
 * @property Mailing $_mailing
 */
class MailQueue
{

    private $_mailing;
    public $emails;

    public function __construct()
    {
        if (Mailing::find()->where(['status' => Mailing::STATUS_SENDING])->one())
            throw new ErrorException('Очередь отправки занята.');

        $this->_mailing = Mailing::find()->where(['status' => Mailing::STATUS_WAITING])->one();

    }


    public function execute()
    {

        if (!$this->_mailing)
            return 'Очередь пуста.';

        if (!$this->_mailing->clients)
            return 'Список получателей пуст.';

        $this->_mailing->status(Mailing::STATUS_SENDING);


        foreach ($this->_mailing->clients as $client) {
            if ($client->email) {

                $data = \Yii::createObject(ProcessMailContent::class, [$this->_mailing->title, $this->_mailing->content, $client])->execute();

                $this->emails[] = $client->email;
                $hash = md5($client->email . time());
                $mail = \Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => 'mailing-html'], [
                            'content' => $data['body'],
                            'statisticGif' => $this->_mailing->unsubscribeLink($hash),]
                    )
                    ->setFrom([\Yii::$app->params['no-reply'] => \Yii::$app->params['no-reply-from']])
                    ->setTo($client->email)
                    ->setSubject($data['subject']);

                if ($this->_mailing->files)
                    foreach ($this->_mailing->files as $file)
                        $mail->attach($file->rootPath, ['fileName' => $file->title]);


                if ($mail->send())
                    \Yii::$app->db->createCommand()->update("{{%mailing_client}}", ['status' => 1], ['mailing_id' => $this->_mailing->id, 'client_id' => $client->id])->execute();
            }
        }

        $this->_mailing->status(Mailing::STATUS_SEND);

        return implode(', ', $this->emails);
    }

}