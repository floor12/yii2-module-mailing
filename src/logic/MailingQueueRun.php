<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 10.05.2017
 * Time: 12:54
 */

namespace floor12\mailing\logic;

use floor12\mailing\models\Mailing;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;

/**
 * Class MailingQueueRun
 */
class MailingQueueRun
{

    private $_mailing;
    private $_module;
    private $_send = [];
    private $_errors = [];

    public function __construct()
    {
        $this->_module = Yii::$app->getModule('mailing');

        if (!$this->_module->fromEmail)
            throw new InvalidConfigException('В конфигурации модуля не указан `fromEmail`.');

        if (!$this->_module->fromName)
            throw new InvalidConfigException('В конфигурации модуля не указан `fromName`.');

        if (!$this->_module->htmlTemplate)
            throw new InvalidConfigException('В конфигурации модуля не указан `htmlTemplate`.');

        if (Mailing::find()->where(['status' => Mailing::STATUS_SENDING])->one())
            throw new ErrorException('Очередь отправки занята.');

        $this->_mailing = Mailing::find()->where(['status' => Mailing::STATUS_WAITING])->one();
    }

    public function execute()
    {

        if (!$this->_mailing)
            return 'Очередь пуста.';

        if (!$this->_mailing->recipients)
            return "Список получателей рассылки id:{$this->_mailing->id} пуст.";

        $this->currentMailingStatusChange(Mailing::STATUS_SENDING);

        $this->replaceLinks();

        foreach ($this->_mailing->recipients as $recipientEmail) {
            $hash = md5($recipientEmail . time());

            $mail = \Yii::$app
                ->mailer
                ->compose(
                    ['html' => $this->_module->htmlTemplate],
                    [
                        'content' => $this->_mailing->content,
                        'gifUrl' => $this->_module->makeStatGifUrl($this->_mailing->id, $hash)
                    ]
                )
                ->setFrom([$this->_module->fromEmail => $this->_module->fromName])
                ->setTo($recipientEmail)
                ->setSubject($this->_mailing->title);

            if (Yii::$app->id != 'testapp' && $this->_mailing->files)
                foreach ($this->_mailing->files as $file)
                    $mail->attach($file->rootPath, ['fileName' => $file->title]);


            if ($mail->send())
                $this->_send[] = $recipientEmail;
            else
                $this->_errors[] = $recipientEmail;
        }

        $this->currentMailingStatusChange(Mailing::STATUS_SEND);

        $ret = "success: " . sizeof($this->_send);
        if ($this->_errors)
            $ret .= "\nerrors: " . sizeof($this->_errors);
        return $ret;
    }

    /**
     * @param int $status
     */
    private function currentMailingStatusChange(int $status)
    {
        $this->_mailing->status = $status;
        if (!$this->_mailing->save(true, ['status']))
            throw new ErrorException('Некорректная попытка выставить статус');
    }

    private function replaceLinks()
    {
        preg_match_all('/<a href=[\"|\']([^"^\']+)[\"|\']/siU', $this->_mailing->content, $matches);
        $urlArray = array_unique($matches[1]);
        if ($urlArray)
            foreach ($urlArray as $url) {
                $redirectUrl = \Yii::createObject(MailingLinkCreate::class, [$this->_mailing->id, $url])->getRedirectLink();
                $this->_mailing->content = str_replace($url, $redirectUrl, $this->_mailing->content);
            }

    }

}