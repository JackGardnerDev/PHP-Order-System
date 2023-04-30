<?php

/**
 * Commission Class
 * 
 * Calculate commission on sales for name, month, year
 * 
 * Reads sales.csv
 * uses Sale Class
 * 
 */

include "Sale.php";

class Commission {
    
    protected $data = []; //Array of Sales

    protected $totalSales = null;
    protected $rate = null;
    protected $commission = null;

    public function __construct($salesFile){

        //Open file
        $file = new SplFileObject($salesFile, 'r');
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

        while(! $file->eof()){
            $ar = $file->fgetcsv();
            if($ar){
                $this->data[] = new Sale($ar);
            }
        }
    }

    /**
     * Find all matching sales and calculate commission
     * 
     * @param array $search ['name', 'month', 'year']
     * @return float 
     * 
     */

    // 
    public function calculate($search){
        if(empty($this->data)){
            throw new Exception("No data loaded");
        }
        $this->totalSales = 0;
        foreach ($this->data as $sale) {
            
            if($sale->getName() == $search['name'] &&
                $sale->getDate()->format('m') == $search['month'] &&
                $sale->getDate()->format('Y') == $search['year']){

                    $this->totalSales += $sale->getAmount();
                }
        }
        $this->commission = $this->totalSales * $this->getRate();
        return $this->commission;
    }

    /**
     * Find the commission rate from the total sales
     * 
     * @param void must be calculated from class already
     * @return float
     * 
     */

    // 
    public function getRate() {
        if(is_null($this->totalSales)){
            throw new Exception("Must call Commission::calculate() first");
        }
        if($this->totalSales > 15000 ){
            $this->rate = 0.02;
        }elseif($this->totalSales > 25000){
            $this->rate = 0.03;
        }elseif($this->totalSales > 30000){
            $this->rate = 0.04;
        }else{
            $this->rate = 0.00;
        }
        return $this->rate;
    }

    // 
    public function getCommission() {
        if(is_null($this->totalSales)){
            throw new Exception("Must call Commission::calculate() first");
        }
        return $this->commission;
    }

    // Get the value of totalSales
    public function getTotalSales() {
        if(is_null($this->totalSales)){
            throw new Exception("Must call Commission::calculate() first");
        }
        return $this->totalSales;
    }

}

?>