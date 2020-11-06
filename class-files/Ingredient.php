<?php
/**
 * Ingredient 
 * 
 */
class Ingredient {
    /**
     * item 
     * 
     */
    private $item = '';

    /**
     * amount 
     * 
     */
    private $amount = 0;

    /**
     * unit 
     * 
     */
    private $unit = '';

    /**
     * useBy 
     * 
     */
    private $useBy = 0;

    /**
     * isExpired 
     * 
     */
    private $isExpired = false;

    /**
     * validationRules 
     * 
     */
    private $validationRules = array(
        'item'   => '/^(.*)$/', 
        'amount' => '/^(\d+)$/',
        'unit'   => '/^(of|grams|ml|slices)$/i',
        'useBy'  => '/^(\d{1,2})\/(\d{1,2})\/(\d{1,2})/'
    );

    /**
     * Constructor
     *
     * @param string $item
     * @param int $amount
     * @param string $unit
     * @param string $useBy
     * @access public
     * @return void
     */
    public function Ingredient ($item   = '',
                                $amount = 0,
                                $unit   = '',
                                $useBy  = null) {
        $this->validateInput('item', $item);
        $this->validateInput('amount', $amount);
        $this->validateInput('unit', $unit);

        // use by is optional
        if ($useBy !== null) {
            $this->validateInput('useBy', $useBy);
            
            // convert the useBy to a timetamp and check if the item is expired
            $this->useBy = strtotime(str_replace('/', '-', $this->useBy));
            if ($this->useBy < time())
                $this->isExpired = true;
        }
    }

    /**
     * getItem 
     * 
     * @access public
     * @return string
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * getAmount 
     * 
     * @access public
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * getUnit 
     * 
     * @access public
     * @return string
     */
    public function getUnit() {
        return $this->unit;
    }

    /**
     * getUseBy 
     * 
     * @access public
     * @return int
     */
    public function getUseBy() {
        return $this->useBy;
    }

    /**
     * getIsExpired 
     * 
     * @access public
     * @return boolean
    */
    public function getIsExpired() {
        return $this->isExpired;
    }

    /**
     * Validates ingredient for input 
     * 
     * @param string $field 
     * @param mixed $input 
     * @access private
     * @return void
     */
    private function validateInput($field = '', $input) {
        if (!preg_match($this->validationRules[$field],
                        $input))
            echo"Invalid input on $field";
        $this->$field = $input;
    }
}
