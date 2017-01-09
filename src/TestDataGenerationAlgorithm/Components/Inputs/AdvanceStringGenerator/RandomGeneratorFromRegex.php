<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/25/2016
 * Time: 4:56 PM
 */


    require_once"BaseDirectory.php";
    require $BASEDIROFPROJECT . "/ExternalLibs/vendor/autoload.php";
    require_once $BASEDIROFPROJECT ."/TestDataGenerationAlgorithm/Components/Inputs/AdvanceStringGenerator/IRandomGeneratorFromRegex.php";
    use ReverseRegex\Lexer;
    use ReverseRegex\Random\SimpleRandom;
    use ReverseRegex\Parser;
    use ReverseRegex\Generator\Scope;
    class RandomGeneratorFromRegex implements IRandomGenerator
{
    /**
     * like "[a-z]{10}"
     * @var string for more info https://github.com/PepijnSenders/ReverseRegex
     */
    protected $RegEx="";

    protected  $lex;
    public function  __construct($RegularExpression,$MinLengthOfResult=0,$MaxLengthOfResult=200)
    {
        $this->RegEx = $RegularExpression;
        $this->MinLengthOfResult = $MinLengthOfResult;
        $this->MaxLengthOfResult = $MaxLengthOfResult;
    }

    /**
     * Get One valid seeded value
     * @return mixed
     */
    public function GetOrGenerateValidSeededValue()
    {
        $this->lex = new Lexer($this->RegEx);
        $result = '';
        // $gen   = new SimpleRandom(10007);
        $gen   = new SimpleRandom(rand(0,$this->MaxLengthOfResult));
        $parser = new Parser($this->lex,new Scope(),new Scope());
        $parser->parse()->getResult()->generate($result,$gen);

        return $result;
    }

    public function GenerateRandomValues($Number)
    {
        $r=array();
        for($i=0;$i<$Number;$i++)
        {
            $r[]=$this->GetOrGenerateValidSeededValue();
        }
        return $r;
    }
}

