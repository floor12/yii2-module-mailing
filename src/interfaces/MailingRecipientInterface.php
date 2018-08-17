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
 * Interface MailingRecipientInterface
 * @package floor12\mailing\interfaces
 */
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

    /** Возращаем строку содержащую email адресс для отправки рассылки.
     *  Если в модели есть поле email, то реализация такая:
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
     *      public function getMailingFullname(): string
     *      {
     *          return "{$this->name} {$this->surname}";
     *      }
     *
     * @return string
     */
    public function getMailingFullname(): string;


}