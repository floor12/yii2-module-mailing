<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2019-03-01
 * Time: 20:34
 */

namespace floor12\mailing\logic;


use floor12\mailing\models\MailingList;
use floor12\mailing\models\MailingListItem;

class AddressLoader
{
    /**
     * Regular expression for filter emails from input string.
     */
    const EMAIL_PATTERN = '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i';
    /**
     * @var array
     */
    protected $addresses = [];
    /**
     * @var string
     */
    protected $emailsString;
    /**
     * @var string
     */
    protected $listId;


    /**
     * AddressLoader constructor.
     * @param MailingList $list
     * @param string $emailsString
     */
    public function __construct(MailingList $list, string $emailsString)
    {
        $this->listId = $list->id;
        $this->emailsString = $emailsString;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $this->parseAddresses();
        return $this->saveAddresses();
    }

    /**
     * @return bool
     */
    protected function saveAddresses()
    {
        if (empty($this->addresses))
            return false;

        foreach ($this->addresses as $email) {
            $listItem = new MailingListItem([
                'list_id' => $this->listId,
                'email' => $email
            ]);
            $listItem->save();
        }
        return true;
    }

    /**
     *
     */
    public function parseAddresses()
    {
        if (preg_match_all(self::EMAIL_PATTERN, $this->emailsString, $matches))
            $this->addresses = $matches[0];
    }

    /**
     * @return array
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }
}