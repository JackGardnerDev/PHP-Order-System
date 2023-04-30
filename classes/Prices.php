<?php

/**
 * Class Prices
 * 
 * Array of Quality
 * 
 * Loaded from prices.xml
 * 
 */

include_once "Quality.php";

Class Prices {

    protected $data = [];

    public function __construct($xmlfile) {
        $file = new XMLReader();
        $file->open($xmlfile);

        //skip to first <quality>
        while($file->read() && $file->name !== 'quality'); 

        while($file->name === 'quality'){

            $element = new SimpleXMLElement($file->readOuterXml());

            //Use Associative array
            $this->data[] = new Quality(trim((string)$element->name), (float)$element->price);

            //Move to the next <food>
            $file->next('quality');    
        }        
    }

    // Return data
    public function getData() {
        return $this->data;
    }

    // Return data as Key-Value Pairs
    public function getDataAsArray() {
        $array = [];
        foreach ($this->data as $quality) {
            $array[$quality->getName()] = $quality->getPrice(); 
        }
        return $array;
    }

    // Get price from name
    public function getPrice($name) {
        $return = null;
        foreach ($this->data as $quality) {
            if($quality->getName() == $name){
                $return = $quality->getPrice();
            }
        }
        if($return){
            return $return;
        }else{
            throw new Exception("Mouse pad quality $name does not exist");
        }
    }

}