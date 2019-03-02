<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 10.05.2017
 * Time: 12:54
 */

namespace floor12\mailing\logic;

use floor12\mailing\models\Mailing;
use floor12\mailing\models\MailingListItemSex;
use Yii;
use yii\base\ErrorException;

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

        if (Mailing::find()->where(['status' => Mailing::STATUS_SENDING])->one())
            throw new ErrorException('Очередь отправки занята.');

        $this->_mailing = Mailing::find()->where(['status' => Mailing::STATUS_WAITING])->one();
    }

    public function execute()
    {

        if (!$this->_mailing)
            return Yii::t('mailing', 'The queue is empty.');

        if (!$this->_mailing->recipients)
            return Yii::t('mailing', 'Mailing list id: {0} is empty.', $this->_mailing->id);

        $this->currentMailingStatusChange(Mailing::STATUS_SENDING);

        $this->replaceLinks();

        foreach ($this->_mailing->recipients as $recipientRow) {
            $hash = md5($recipientRow['email'] . time());

            $mail = \Yii::$app
                ->mailer
                ->compose(
                    ['html' => $this->_module->htmlTemplate],
                    [
                        'content' => $this->proccessVars($this->_mailing->content, $recipientRow),
                        'gifUrl' => $this->_module->makeStatGifUrl($this->_mailing->id, $hash),
                        'unsubscribeUrl' => MailingUnsubscribe::makeUrl($recipientRow['email'], (int)$this->_mailing->list_id)
                    ]
                )
                ->setFrom([$this->_module->fromEmail => $this->_module->fromName])
                ->setTo($recipientRow['email'])
                ->setSubject($this->_mailing->title);

            if (Yii::$app->id != 'testapp' && $this->_mailing->files)
                foreach ($this->_mailing->files as $file)
                    $mail->attach($file->rootPath, ['fileName' => $file->title]);


            if ($mail->send())
                $this->_send[] = $recipientRow['email'];
            else
                $this->_errors[] = $recipientRow['email'];
        }

        $this->currentMailingStatusChange(Mailing::STATUS_SEND);

        $ret = "success: " . sizeof($this->_send);
        if ($this->_errors)
            $ret .= "\nerrors: " . sizeof($this->_errors);
        return $ret;
    }

    /**
     * Заменяем переменные в теле письма, если они есть.
     * Replace variables in the body of the letter, if any.
     * @param string $content
     * @param array $recipient
     * @return string
     */
    private function proccessVars(string $content, array $recipientRow): string
    {
        if (isset($recipientRow['fullname']))
            $content = str_replace("[%username]", $recipientRow['fullname'], $content);
        else
            $content = str_replace("[%username]", 'пользователь', $content);

        if (isset($recipientRow['sex']) && $recipientRow['sex']) {
            if ($recipientRow['sex'] == MailingListItemSex::MAN) {
                $content = str_replace("[%Dear]", 'Уважаемый', $content);
                $content = str_replace("[%dear]", 'уважаемый', $content);
            } else {
                $content = str_replace("[%Dear]", 'Уважаемая', $content);
                $content = str_replace("[%dear]", 'уважаемая', $content);
            }
        } else {
            $content = str_replace("[%Dear]", 'Уважаемый(ая)', $content);
            $content = str_replace("[%dear]", 'уважаемый(ая)', $content);
        } // ToDo: !!!!!!!!!!!!!!!!!!!!!!!!!!!!
        return $content;
    }

    /**
     * @param int $status
     */
    private function currentMailingStatusChange(int $status)
    {
        $this->_mailing->status = $status;
        if (!$this->_mailing->save(true, ['status']))
            throw new ErrorException(Yii::t('mailing', 'Incorrect attempt to set status.'));
    }

    private function replaceLinks()
    {
        preg_match_all('/<a href=[\"]([^"^\']+)[\"]/siU', $this->_mailing->content, $matches);
        $urlArray = array_unique($matches[1]);
        if ($urlArray)
            foreach ($urlArray as $url) {
                $redirectUrl = \Yii::createObject(MailingLinkCreate::class, [$this->_mailing->id, $url])->getRedirectLink();
                $this->_mailing->content = str_replace("\"{$url}\"", "\"{$redirectUrl}\"", $this->_mailing->content);
            }

    }

}