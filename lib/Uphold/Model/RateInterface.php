<?php

namespace Uphold\Model;

/**
 * RateInterface.
 */
interface RateInterface
{
    /**
     * Gets rate ask.
     *
     * @return $ask
     */
    public function getAsk();

    /**
     * Gets rate bid.
     *
     * @return $bid
     */
    public function getBid();

    /**
     * Gets rate currency.
     *
     * @return $currency
     */
    public function getCurrency();

    /**
     * Gets rate pair.
     *
     * @return $pair
     */
    public function getPair();
}
