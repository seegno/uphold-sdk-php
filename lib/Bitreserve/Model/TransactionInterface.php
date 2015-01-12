<?php

namespace Bitreserve\Model;

/**
 * TokenInterface.
 */
interface TransactionInterface
{
    /**
     * Gets transaction creation date.
     *
     * @return $createdAt
     */
    public function getCreatedAt();

    /**
     * Gets transaction denomination.
     *
     * @return $denomination
     */
    public function getDenomination();

    /**
     * Gets transaction destinantion.
     *
     * @return $destination
     */
    public function getDestination();

    /**
     * Gets transaction id.
     *
     * @return $id
     */
    public function getId();

    /**
     * Gets transaction message.
     *
     * @return $message
     */
    public function getMessage();

    /**
     * Gets transaction origin.
     *
     * @return $origin
     */
    public function getOrigin();

    /**
     * Gets transaction params.
     *
     * @return $params
     */
    public function getParams();

    /**
     * Gets transaction refunded by id.
     *
     * @return $refundedById
     */
    public function getRefundedById();

    /**
     * Gets transaction current status.
     *
     * @return $status
     */
    public function getStatus();

    /**
     * Sets transaction card.
     *
     * @return $this
     */
    public function setCardId($cardId);

    /**
     * Cancel current transaction.
     *
     * @return $params
     */
    public function cancel();

    /**
     * Execute current transaction.
     *
     * @return $this
     */
    public function commit();
}
