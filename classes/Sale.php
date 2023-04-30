<?php

/**
 *  Sales Data Class
 * 
 * interaction with sales.csv
 * 
 */

class Sale {

    // 
    protected $name;
    protected $date;
    protected $amount;

    // public function __construct($name, $date, $amount)
    //Validation: Are there 3 elements?, Is the first one a string, not empty?, Is the second one a valid date?, Is the third one a valid float?
    public function __construct($arData) {
            $this->name = $arData[0];
            $this->date = DateTime::createFromFormat("Y-m-d", $arData[1]);
            $this->amount = (float)$arData[2];
    }

    // Get the value of name
    public function getName() {
        return $this->name;
    }

    // Get the value of date
    public function getDate() {
        return $this->date;
    }

    // Get the value of amount
    public function getAmount() {
        return $this->amount;
    }

}