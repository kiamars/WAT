<?php

    /**
     * Created by PhpStorm.
     * User: computer
     * Date: 01/03/2017
     * Time: 01:51 AM
     */
    interface IRandomGenerator
    {
        /**
         * Get One valid seeded value
         * @return mixed
         */
        public function GetOrGenerateValidSeededValue();

        public function GenerateRandomValues($Number);
    }