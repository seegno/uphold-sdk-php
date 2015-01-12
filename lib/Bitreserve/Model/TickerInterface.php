<?php

namespace Bitreserve\Model;

/**
 * TokenInterface.
 */
interface TickerInterface
{
    /**
     * Gets ticker ask.
     *
     * @return $ask
     */
    public function getAsk();

    /**
     * Gets ticker bid.
     *
     * @return $bid
     */
    public function getBid();

    /**
     * Gets ticker currency.
     *
     * @return $currency
     */
    public function getCurrency();

    /**
     * Gets ticker pair.
     *
     * @return $pair
     */
    public function getPair();
}
