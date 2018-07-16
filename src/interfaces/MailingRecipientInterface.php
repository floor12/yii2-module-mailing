<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 16.07.2018
 * Time: 12:15
 */

namespace floor12\mailing\interfaces;

interface MailingRecipientInterface
{

    /** Возвращает массив для формирования dropdown листа выбора получатетелй в форме редактирования рассылок
     * @return array
     */
    public static function getMailingList(): array;

    /** Возращаем адекватное название модели чтобы выводить его в форме редактирования рассылок
     * @return string
     */
    public static function getMailingLabel(): string;

    /** Возращаем строку содержащую email адресс для отправки рассылки
     * @return string
     */
    public function getMailingEmail(): string;


}