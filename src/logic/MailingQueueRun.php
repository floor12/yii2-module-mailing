<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 10.05.2017
 * Time: 12:54
 */

namespace floor12\mailing\logic;

use floor12\mailing\models\enum\MailingListItemSex;
use floor12\mailing\models\enum\MailingStatus;
use floor12\mailing\models\Mailing;
use Yii;
use yii\base\ErrorException;

/**
 * Class MailingQueueRun
 */
class MailingQueueRun
{

    private $_mailing;
    private $_content;
    private $_module;
    private $_send = [];
    private $_errors = [];
    private $contentImages = [];
    private $cids = [];

    public function __construct()
    {
        $this->_module = Yii::$app->getModule('mailing');

        if (Mailing::find()->where(['status' => MailingStatus::STATUS_SENDING])->one())
            throw new ErrorException('Очередь отправки занята.');

        $this->_mailing = Mailing::find()->where(['status' => MailingStatus::STATUS_WAITING])->one();
    }

    public function execute()
    {

        if (!$this->_mailing)
            return Yii::t('app.f12.mailing', 'The queue is empty.');

        if (!$this->_mailing->recipients)
            return Yii::t('app.f12.mailing', 'Mailing list id: {0} is empty.', $this->_mailing->id);

        //  $this->currentMailingStatusChange(MailingStatus::STATUS_SENDING);

        $this->getContentImages();

        $this->replaceLinks();

        foreach ($this->_mailing->recipients as $recipientRow) {
            $hash = md5($recipientRow['email'] . time());

            $content = strval($this->_mailing->content);

            $this->cids = [];

            $message = \Yii::$app
                ->mailer
                ->compose()
                ->setFrom([$this->_module->fromEmail => $this->_module->fromName])
                ->setTo($recipientRow['email'])
                ->setSubject($this->_mailing->title);

            if (Yii::$app->id != 'testapp' && $this->_mailing->files)
                foreach ($this->_mailing->files as $file) {
                    $filename = "/files/default/get?hash=" . $file->hash;
                    if (in_array($filename, $this->contentImages)) {
                        $this->cids[$message->embed($file->rootPath)] = $filename;
                    } else
                        $message->attach($file->rootPath, ['fileName' => $file->title]);
                }

            if ($this->cids)
                foreach ($this->cids as $cid => $filename) {
                    $content = str_replace($filename, $cid, $content);
                }

            $html = Yii::$app->getView()->render($this->_module->htmlTemplate, [
                'content' => $this->proccessVars($content, $recipientRow),
                'gifUrl' => $this->_module->makeStatGifUrl($this->_mailing->id, $hash),
                'unsubscribeUrl' => MailingUnsubscribe::makeUrl($recipientRow['email'], (int)$this->_mailing->list_id)

            ]);

            $message->setHtmlBody($html);

            if ($message->send())
                $this->_send[] = $recipientRow['email'];
            else
                $this->_errors[] = $recipientRow['email'];
        }

        $this->currentMailingStatusChange(MailingStatus::STATUS_SEND);

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
        }
        return $content;
    }

    /**
     * @param int $status
     */
    private function currentMailingStatusChange(int $status)
    {
        $this->_mailing->status = $status;
        if (!$this->_mailing->save(true, ['status']))
            throw new ErrorException(Yii::t('app.f12.mailing', 'Incorrect attempt to set status.'));
    }

    private function getContentImages()
    {
        preg_match_all('/[\(|\"](\/files\/default\/get.+)[\)|\"]/siU', $this->_mailing->content, $matches);
        $this->contentImages = $matches[1];
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