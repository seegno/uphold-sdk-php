<?php

namespace Uphold\Model;

/**
 * AccountInterface.
 */
interface AccountInterface
{
    /**
     * Gets account's currency.
     *
     * @return $currency
     */
    public function getCurrency();

    /**
     * Gets account's id.
     *
     * @return $id
     */
    public function getId();

    /**
     * Gets account's label.
     *
     * @return $label
     */
    public function getLabel();

    /**
     * Gets account's status.
     *
     * @return $status
     */
    public function getStatus();

    /**
     * Gets account's type (ach, card or sepa).
     *
     * @return $type
     */
    public function getType();
}
