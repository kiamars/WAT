<?php
/**
 * Created by PhpStorm.
 * User: Computer
 * Date: 02/25/2016
 * Time: 09:23 PM
 */

class Constants{
    const OPEQUAL='Equal';
    const OPIDENTICAL='Identical';
    const OPNOTEQUAL='Notequal';
    const OPNOTIDENTICAL='Notidentical';
    const OPLESSTHAN='Lessthan';
    const OPGREATTHAN='Greaterthan';
    const OPLESSTHANOREQUAL='Lessthanorequalto';
    const OPGREATTHANOREQUAL='Greaterthanorequalto';
    const OPBOOLFUNCTION='boolean';
}

class VariableType{
    const BOOLEAN='boolean';
    const INTEGER='integer';
    const FLOAT='float';
    const STRING='string';
    const _ARRAY='array';
    const _CustomTime="CustomTime";
//    const OBJECT='object';
//    const _Null='null';
    const DOUBLE='double';
}

/**
 * Class LOT logical Operation Type
 */
class LOT{
    const OPR_AND1='and';
    const OPR_OR1='or';
    const OPR_AND2='&&';
    const OPR_OR2='||';
    Const OPR_XOR='xor';
    const OPR_not='!';
}