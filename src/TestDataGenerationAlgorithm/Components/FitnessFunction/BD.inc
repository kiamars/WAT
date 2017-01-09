<?php
/**
 * Created by PhpStorm.
 * User: Computer
 * Date: 02/25/2016
 * Time: 11:35 PM
 */

class BD
{
    public static function BoolCondition($BoolCondition){
         $RetVal=new FFER;
         $RetVal->Result=$BoolCondition;

        if ($BoolCondition)
        {
            $RetVal->TFit=0;
            $RetVal->FFit=1;
        }
        else
        {
            $RetVal->TFit=1;
            $RetVal->FFit=0;
        }

        return $RetVal;
    }

    public static function Equal($opr1,$opr2)
    {
        $RetVal=new FFER;

        //Input type is String
        if(is_string($opr1)||is_string($opr2))
        {
            $RetVal->TFit=levenshtein($opr1,$opr2);
            if($RetVal->TFit==0)
                $RetVal->FFit=1;
            else
                $RetVal->FFit=0;
        }
        else if(is_numeric($opr1)&& is_numeric($opr2))//numeric
        {
            $opr1=$opr1+0;$opr2=$opr2+0;//f you want the numerical value of a string, this will return a float or int value
            if(abs($opr1 - $opr2)==0)
            {
                $RetVal->TFit = 0;
                $RetVal->FFit = abs($opr1 - $opr2)+1;
            }
            else{
                $RetVal->TFit= abs($opr1 - $opr2)+1;
                $RetVal->FFit = 0;
            }
        }
        $RetVal->Result=($opr1==$opr2);
        return $RetVal;
    }
    public static function NotEqual($opr1,$opr2)
    {
        $RetVal=new FFER;
        //Input type is String
        if(is_string($opr1)||is_string($opr2))
        {
            if(levenshtein($opr1,$opr2)==0)
                $RetVal->TFit=1;
            else
                $RetVal->TFit=0;
            $RetVal->FFit=levenshtein($opr1,$opr2);

        }
        else if(is_numeric($opr1)&& is_numeric($opr2))//numeric
        {
            $opr1=$opr1+0;$opr2=$opr2+0;//f you want the numerical value of a string, this will return a float or int value
            if(abs($opr1 - $opr2)!=0)
            {
                $RetVal->TFit = 0;
                $RetVal->FFit = 1;
            }
            else{
                $RetVal->TFit= 1;
                $RetVal->FFit = 0;
            }
        }
        $RetVal->Result=($opr1!=$opr2);
        return $RetVal;
    }

    public static function Identical($opr1,$opr2){
        $RetVal=null;
        $RetVal= self::Equal($opr1,$opr2);
        $RetVal->Result=($opr1===$opr2);
        return $RetVal;
    }
    public static function NotIdentical($opr1,$opr2){
        $RetVal=null;
        $RetVal= self::NotEqual($opr1,$opr2);
        $RetVal->Result=($opr1!==$opr2);
        return $RetVal;
    }

    public static function GreaterThan($opr1,$opr2)
    {
        $RetVal=new FFER;
        //Input type is String
        if(is_string($opr1)||is_string($opr2))
        {
            $c=self::g($opr1);
            $d=self::g($opr2);

            if($c>$d)
            {
                $RetVal->TFit = 0;
                $RetVal->FFit = abs($c - $d) + 1;
            }
            else
            {
                $RetVal->TFit= abs($c-$d)+1;
                $RetVal->FFit = 0;
            }
        }
        else if(is_numeric($opr1)&& is_numeric($opr2))//numeric
        {
            $opr1=$opr1+0;$opr2=$opr2+0;//f you want the numerical value of a string, this will return a float or int value
            if($opr1>$opr2)
            {
                $RetVal->TFit = 0;
                $RetVal->FFit = abs($opr1 - $opr2)+1;
            }
            else{
                $RetVal->TFit= abs($opr1 - $opr2)+1;
                $RetVal->FFit = 0;
            }
        }
        $RetVal->Result=($opr1>$opr2);
        return  $RetVal;
    }

    public static function LessThan($opr1,$opr2)
    {
        return self::GreaterThan($opr2,$opr1);
    }

    public static function GreaterOrEqual($opr1,$opr2)
    {
        $RetVal=new FFER;
        //Input type is String
        if(is_string($opr1)||is_string($opr2))
        {
            $c=self::g($opr1);
            $d=self::g($opr2);

            if($c>=$d)
            {
                $RetVal->TFit = 0;
                $RetVal->FFit = abs($c - $d) + 1;
            }
            else
            {
                $RetVal->TFit= abs($c-$d)+1;
                $RetVal->FFit = 0;
            }
        }
        else if(is_numeric($opr1)&& is_numeric($opr2))//numeric
        {
            $opr1=$opr1+0;$opr2=$opr2+0;//f you want the numerical value of a string, this will return a float or int value
            if($opr1>=$opr2)
            {
                $RetVal->TFit = 0;
                $RetVal->FFit = abs($opr1 - $opr2)+1;
            }
            else{
                $RetVal->TFit= abs($opr1 - $opr2)+1;
                $RetVal->FFit = 0;
            }
        }
        $RetVal->Result=($opr1>= $opr2);
        return  $RetVal;
    }
    public static function LessOrEqual($opr1,$opr2)
    {
        return  self::GreaterOrEqual($opr2,$opr1);
    }

    public static function LOpAND( FFER $opr1,FFER $opr2)
    {
        $RetVal=new FFER;
        $RetVal->Result=($opr1->Result&&$opr2->Result);
        $RetVal->TFit=$opr1->TFit+$opr2->TFit;
        $RetVal->FFit=min($opr1->FFit,$opr2->FFit);
        return  $RetVal;
    }
    public static function LOpOR(FFER $opr1, $opr2)
    {
        $RetVal=new FFER;
        $RetVal->Result=($opr2->Result || $opr1->Result);
        $RetVal->TFit = min($opr1->TFit ,$opr2->TFit);
        $RetVal->FFit = $opr1->FFit+$opr2->FFit;
        return  $RetVal;
    }

    /**
     * @param $sting
     * @param int $W
     * @return int
     */
    public static function g($sting,$W=127)
    {
            $n=-1;
            try {
                $n=0;
                $l=mb_strlen($sting);

                for ($i = 0; $i < $l; $i++) {
                    $n+=ord($sting[$i])*pow($W,($l-$i-1));

                }

            }catch (Exception $e)
            {
                echo 'in function ConvertToNumber() '.__FILE__.'error['.$e->getMessage().']';
            }

            //return log10($n);
            return $n;
        }
}

/**
 * Class Fitness function evaluation result
 * @package TicksoftPHPSBTDF\Commons
 */
class FFER
{
    /**
     * @var bool result
     */
    public  $Result=false;
    /**
     * @var double true fitness
     */
    public  $TFit;
    /**
     * @var double False fitness
     */
    public $FFit;

}