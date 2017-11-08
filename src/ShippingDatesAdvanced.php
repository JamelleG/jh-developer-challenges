<?php

namespace Jh\Shipping;

/**
 * Class ShippingDates
 * @package Jh\Shipping
 */
class ShippingDatesAdvanced implements ShippingDatesInterface
{
    const SHIP_DAYS = 3;
    const DAYS_IN_WEEK = 7;
    const WEEKEND_START = 6;
    const PROCESS_ENDHOUR = 17;
    
    
    
    /**
     * Calculates delivery date using a order date.
     * 
     * @param \DateTime $orderDate
     * @return \DateTime
     */
    public function calculateDeliveryDate(\DateTime $orderDate)
    {
        
        $dispatchDate = self::calculateDispatchDate($orderDate);
        $deliveryDate = clone $dispatchDate;
        
        for ($businessDays = 0; $businessDays < self::SHIP_DAYS;) {

            $deliveryDate->add(new \DateInterval('P1D'));

            if (self::isValidDate($deliveryDate)) {
                $businessDays++;
            }

        }

        return $deliveryDate;
    }

    /**
     * Calculates dispatch date using order date.
     *
     * @param \DateTime $orderDate
     * @return \DateTime
     */
    private function calculateDispatchDate(\DateTime $orderDate)
    {
        $date = clone $orderDate;

        if($date->format('H') >= self::PROCESS_ENDHOUR) {
            $date->add(new \DateInterval('P1D'));
        }

        if ($date->format('N') >= self::WEEKEND_START) {
            $daysTillNextProcess = self::DAYS_IN_WEEK - $date->format('N') + 1;

            $date->add(new \DateInterval('P'. $daysTillNextProcess .'D'));
        }

        return $date;
    }
    /**
     * Checks if a date is valid using pre-defined rules
     * 
     * @param \DateTime $date
     * @return boolean
     */
    private function isValidDate(\DateTime $date)
    {
        if ($date->format('N') >= self::WEEKEND_START) {
            return false;
        }
        return true;
    }
}
