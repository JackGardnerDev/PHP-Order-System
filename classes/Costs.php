<?php

/**
 * Class Costs
 * 
 * This class brings together all the costs for calculating the cost of carpet
 * 
 */

include_once 'Pads.php';
include_once 'Prices.php';

class Costs {
    
    //constants
    const M2_DIV = 200.00;
    const WASTE = 0.12;
    const GST = 0.10;

    protected $totalArea;
    protected $m2;
    protected $quality; 
    protected $price; 
    protected $totalCost;
    protected $discount_pc;
    protected $discount_amount;
    protected $excl_cost;
    protected $gst;
    protected $incl_cost;

    /**
     * Load all variables and calculate costs
     * 
     * @param Pads $pads
     * @param string $quality
     * @param float $discount_pc
     * 
     */
    
    public function calcCosts($pads, $quality, $discount_pc) {

        //Inputs
        $this->totalArea = $pads->getTotalArea();
        $this->m2 = $this->getM2();
        $prices = new Prices("data/prices.xml");
        $this->quality = $quality;
        $this->price = $prices->getPrice($quality);

        //Calculations
        if(empty($discount_pc)){
            $this->discount_pc = 0;
        }else{
            $this->discount_pc = $discount_pc;
        }
        $this->totalCost = $this->m2 * $this->price;
        $this->discount_amount = $this->totalCost * ($this->discount_pc/100);
        
        $this->excl_cost = $this->totalCost - $this->discount_amount;
        $this->gst = $this->excl_cost * self::GST;
        $this->incl_cost = $this->excl_cost + $this->gst;

    }

    // Get the value of totalArea 
    public function getTotalArea() {
        return $this->totalArea;
    }

    // Calculate total m2
    public function getM2() {
        return ceil($this->getTotalArea() * (1 + self::WASTE) / self::M2_DIV);
    }

    // Get the value of totalCost
    public function getTotalCost() {
        return number_format($this->totalCost, 2);
    }

    // Get the value of discount_amount
    public function getDiscount_amount() {
        return number_format($this->discount_amount, 2);
    }

    // Get the value of excl_cost 
    public function getExcl_cost() {
        return number_format($this->excl_cost, 2);
    }

    // Get the value of gst
    public function getGst() {
        return number_format($this->gst, 2);
    }

    // Get the value of incl_cost
    public function getIncl_cost() {
        return number_format($this->incl_cost, 2);
    }

    // Get quality from Price
    public function getQuality(){
        return $this->quality;
    }

}

?>