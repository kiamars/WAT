<?php

    /**
     * Created by PhpStorm.
     * User: computer
     * Date: 12/22/2016
     * Time: 01:41 PM
     */

    require_once "Input.php";

    /**
     * Class CIT Custom Input Type
     */
    class CIT extends Input
    {

        public $Input = null;

        function __construct(Input $CustomInputType, $NumberOfItInEachGeneration = 2)
        {
            $this->NumberInEachGeneration=$NumberOfItInEachGeneration;
            $this->Input = $CustomInputType;
            parent::__construct($this->Input->IndexInInputVector, $this->Input->Name, $this->Input->getInputType());

            if ($this->Input->CurrentValue == null)
                $this->Input->SetCurrentValue($this->Input->GetRandomValue());
        }

        public function SetCurrentValue($value)
        {
            $this->Input->SetCurrentValue($value);
        }

        public function GetRandomValue()
        {
            return $this->Input->GetRandomValue();
        }

        public function AddDefaultValue($NewDefaultValue)
        {
            $this->Input->AddDefaultValue($NewDefaultValue);
        }
    }