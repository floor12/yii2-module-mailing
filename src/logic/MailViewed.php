<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 23.12.2017
 * Time: 20:00
 */

namespace floor12\mailing\logic;

use app\models\mailing\MailView;

class MailViewed
{
    private $_id, $_hash;

    public function __construct($id, $hash)
    {
        $this->_id = $id;
        $this->_hash = $hash;
    }

    public function execute()
    {
        $view = new MailView();
        $view->mailing_id = $this->_id;
        $view->hash = $this->_hash;
        $view->save();
    }
}