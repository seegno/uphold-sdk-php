<?php

namespace Uphold\Model;

/**
 * TransactionInterface.
 */
interface TransactionInterface
{
    /**
     * Get transaction creation date.
     *
     * @return $createdAt
     */
    public function getCreatedAt();

    /**
     * Get transaction denomination.
     *
     * @return $denomination
     */
    public function getDenomination();

    /**
     * Get transaction destinantion.
     *
     * @return $destination
     */
    public function getDestination();

    /**
     * Get transaction id.
     *
     * @return $id
     */
    public function getId();

    /**
     * Get transaction message.
     *
     * @return $message
     */
    public function getMessage();

    /**
     * Get transaction origin.
     *
     * @return $origin
     */
    public function getOrigin();

    /**
     * Get transaction params.
     *
     * @return $params
     */
    public function getParams();

    /**
     * Get transaction refunded by id.
     *
     * @return $refundedById
     */
    public function getRefundedById();

    /**
     * Get transaction current status.
     *
     * @return $status
     */
    public function getStatus();

    /**
     * Get transaction type.
     *
     * @return $type
     */
    public function getType();

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

    /**
     * Resend current transaction.
     *
     * @return $this
     */
    public function resend();
}
