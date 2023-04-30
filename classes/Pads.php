<?php

/**
 * class Pads
 * 
 * builds array of class Pad
 * 
 */

include_once "Pad.php";

class Pads {

    // 
    protected $pads = [];
    protected $totalArea = null;

    // Add one pad at a time
    public function addPad($width, $length) {
        try{
            $temp = new Pad($width, $length);
            $this->pads[] = $temp;

        } catch (Exception $e){
            //log error
        }
    }

    // Add array of pads from array of widths and lengths, such as from user input form
    public function addPads($arrWidths, $arrLengths) {
        array_map(array($this, 'addPad'), $arrWidths, $arrLengths);

    }

    // Return the number of pads
    public function numPads() {
        return count($this->pads);
    }

    // Calculate total area, If this has already been calculated just return it
    public function getTotalArea() {
        if(empty($this->totalArea)){
            $this->totalArea = 0;
            foreach($this->pads as $pad){
                $this->totalArea += $pad->getArea();
            }
        }
        return $this->totalArea;
    }

}