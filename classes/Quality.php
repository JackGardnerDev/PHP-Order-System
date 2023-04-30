<?php

/**
 * class Quality
 * 
 * Return price, based on quality
 */

class Quality {

    // 
    protected $name;
    protected $price;

    // 
    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }

    // 
    public function getPrice() {    
        return $this->price;
    }

    // Get the value of quality
    public function getName() {
        return $this->name;
    }

}