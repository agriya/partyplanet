<?php
// Original code from http://snook.ca/archives/php/url-shortener/#c63597
// rajesh_04ag02 // 2009-06-10 // minor function name changes for aesthetics
// Usage: BaseIntEncoder::encode(100)
//   and  BaseIntEncoder::decode('3J')
include_once APP.'vendors/PEAR/Math/BigInteger.php';
class BaseIntEncoder
{
    //const $codeset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //readable character set excluded (0,O,1,l)
	
	

    const codeset = '23456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
    static private function _bcFloor($x)
    {
		$_x = new Math_BigInteger($x);
		$_y = new Math_BigInteger(1);
		$_result = $_x->multiply($_y);
        return $_result->toString();
    }
    static private function _bcCeil($x)
    {
        $floor = _bcFloor($x);
		
		$_a = new Math_BigInteger($x);
		$_b = new Math_BigInteger($floor);
		$_subtract = $_a->subtract($_b);
		
		$_x = new Math_BigInteger($floor);
		$_y = new Math_BigInteger(ceil($_subtract->toString()));
		$_result = $_x->add($_y);
		
        return $_result->toString();
    }
    static private function _bcRound($x)
    {
        $floor = _bcFloor($x);
		
        $_a = new Math_BigInteger($x);
		$_b = new Math_BigInteger($floor);
		$_subtract = $_a->subtract($_b);
		
		$_x = new Math_BigInteger($floor);
		$_y = new Math_BigInteger(round($_subtract->toString()));
		$_result = $_x->add($_y);
		
        return $_result->toString();
    }
    static function encode($n)
    {
		
        $base = strlen(self::codeset);
        $converted = '';
        while ($n > 0) {
			
			$_x = new Math_BigInteger($n);
			$_y = new Math_BigInteger($base);
			list(,$_result) = $_x->divide($_y);

			$_a = new Math_BigInteger($n);
			$_b = new Math_BigInteger($base);
			list($_divide,) = $_a->divide($_b);

			$converted = substr(self::codeset, $_result->toString() , 1) . $converted;
            $n = self::_bcFloor( $_divide->toString());
        }
		
        return $converted;
    }
    static function decode($code)
    {
        $base = strlen(self::codeset);
        $c = '0';
        for ($i = strlen($code); $i; $i--) {
		
			//multiply for pow ($base * (i  -1 ))
			$_a = new Math_BigInteger($base);
			$_b = new Math_BigInteger($i - 1);
			$_multiply = $_a->multiply($_b);
		
			//pow calculation
			$_a1 = new Math_BigInteger($base);
			$_b1 = new Math_BigInteger($i - 1);
			$_pow = $_a1->modPow($_b1,$_multiply);

		
			//multiply the pow values and substring values
			$_x = new Math_BigInteger(strpos(self::codeset, substr($code, (-1 * ($i - strlen($code))) , 1)));
			$_y = new Math_BigInteger($_pow->toString());
			$_mul = $_x->multiply($_y);
			
			
			// Add the multiplied values and c
			$_x1= new Math_BigInteger($c);
			$_y1 = new Math_BigInteger($_mul->toString());
			$_result = $_x1->add($_y1);
		
         	//store new values into c variable
			$c = $_result->toString();
        }
		
			$_x1= new Math_BigInteger($c);
			$_y1 = new Math_BigInteger(1);
			$_result = $_x1->multiply($_y1);
		
         return $_result->toString();
    }
}
?>