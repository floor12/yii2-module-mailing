<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 16.07.2018
 * Time: 12:15
 */

namespace floor12\mailing\interfaces;

/** Этот интерфейс необходимо имплементировать тем моделям ActiveRecord проекта,
 *  которые имеют поле с email адресом, и должны учавствовать в рассылках
 *
 * This interface must be implemented for those ActiveRecord project models
 * that have a field with an email address, and should participate in mailing lists.
 *
 * Interface MailingRecipientInterface
 * @package floor12\mailing\interfaces
 */
interface MailingRecipientInterface
{

    /** Возвращает массив для формирования dropdown листа выбора получатетелй в форме редактирования рассылок
     * Returns an array to form a dropdown list of recipients in the form of editing mailings
     * @return array
     */
    public static function getMailingList(): array;

    /** Возращаем адекватное название модели чтобы выводить его в форме редактирования рассылок
     * We return the adequate name of the model to display it in the form of editing mailings
     * @return string
     */
    public static function getMailingLabel(): string;

    /** Возращаем строку содержащую email адресс для отправки рассылки.
     *  Если в модели есть поле email, то реализация такая:
     *
     * Returns a string containing the email address to send the newsletter.
     * If the model has an email field, the implementation is as follows:
     *
     *      public function getMailingEmail(): string
     *      {
     *          return $this->email;
     *      }
     *
     * @return string
     */
    public function getMailingEmail(): string;

    /** Возращаем строку содержащую полное имя получателя для рассылки.
     *  Если в модели есть поле name и surname, то реализация может выглядить так:
     *
     * Return the string containing the full name of the recipient for distribution.
     * If the model has a field name and surname, then the implementation may look like this:
     *
     *      public function getMailingFullname(): string
     *      {
     *          return "{$this->name} {$this->surname}";
     *      }
     *
     * @return string
     */
    public function getMailingFullname(): string;


}