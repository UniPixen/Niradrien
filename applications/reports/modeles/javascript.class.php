<?php
	class javascript {
		public static function quote($js, $forUrl = false) {
			if ($forUrl) {
				return strtr($js, array('%'=>'%25',"\t"=>'\t',"\n"=>'\n',"\r"=>'\r','"'=>'\"','\''=>'\\\'','\\'=>'\\\\','</'=>'<\/'));
			}

			else {
				return strtr($js, array("\t"=>'\t',"\n"=>'\n',"\r"=>'\r','"'=>'\"','\''=>'\\\'','\\'=>'\\\\','</'=>'<\/'));
			}
		}

		public static function encode($value) {
			if (is_string($value)) {
				if (strpos($value,'js:') === 0) {
					return substr($value,3);
				}
				
				elseif (preg_match('/^([0-9]{1,})(.[0-9]{1,})?+$/', $value)) {
					return $value;
				}
				
				else {
					return "'" . self::quote($value) . "'";
				}
			}

			elseif ($value === null) {
				return 'null';
			}

			elseif (is_bool($value)) {
				return $value?'true':'false';
			}

			elseif (is_integer($value)) {
				return "$value";
			}

			elseif (is_float($value)) {
				if ($value===-INF) {
					return 'Number.NEGATIVE_INFINITY';
				}
				
				elseif($value===INF) {
					return 'Number.POSITIVE_INFINITY';
				}
				
				else {
					return "$value";
				}
			}

			elseif (is_object($value)) {
				return self::encode(get_object_vars($value));
			}
			
			elseif (is_array($value)) {
				$es = array();

				if (($n = count($value)) > 0 && array_keys($value) !== range(0, $n - 1)) {
					foreach($value as $k => $v) {
						$es[] = "'".self::quote($k)."':".self::encode($v);
					}

					return '{' . implode(',', $es) . '}';
				}

				else {
					foreach($value as $v) {
						$es[] = self::encode($v);
					}

					return '[' . implode(',', $es) . ']';
				}
			}

			else {
				return '';
			}
		}
	}
?>