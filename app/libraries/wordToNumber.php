<?
class WordToNumber {

    public $modifiers = array(
        'hundred' => 100,
    );

    public $negatives = array(
        'minus' => true,
        'negative' => true,
    );

    public $numbers = array(
        'zero'  => 0,
        'one'   => 1,
        'two'   => 2,
        'three' => 3,
        'four'  => 4,
        'five'  => 5,
        'six'   => 6,
        'seven' => 7,
        'eight' => 8,
        'nine'  => 9,
        'ten'   => 10,
        'eleven' => 11,
        'twelve' => 12,
        'thirteen' => 13,
        'fourteen' => 14,
        'fifteen'  => 15,
        'sixteen'  => 16,
        'seventeen' => 17,
        'eighteen'  => 18,
        'nineteen'  => 19,
        'twenty'    => 20,
        'thirty'    => 30,
        'forty'    => 40,
        'fifty'     => 50,
        'sixty'     => 60,
        'seventy'   => 70,
        'eighty'    => 80,
        'ninety'    => 90,    
    );

    public $powers = array(
        'thousand' => 1000,
        'million'  => 1000000,
        'billion'  => 1000000000,
    );

    public function __construct() {
    }

    public function parse($string) {
        $string = $this->prepare($string);
        $parts = preg_split('#\s+#', $string, -1, PREG_SPLIT_NO_EMPTY);
        $buffer = 0;
        $lastPower = 1;
        $powers = array(
            1 => 0,
        );
        $isNegative = false;
        foreach ($parts as $part) {
            if (isset($this->negatives[$part])) {
                $isNegative = true;
            } elseif (isset($this->numbers[$part])) {
                $buffer += $this->numbers[$part];
            } elseif (isset($this->modifiers[$part])) {
                $buffer *= $this->modifiers[$part];
            } elseif (isset($this->powers[$part])) {
                if ($buffer == 0) {
                    //Modify last power
                    $buffer = $powers[$lastPower];
                    unset($powers[$lastPower]);
                    $power = $lastPower * $this->powers[$part];
                    $powers[$power] = $buffer;
                    $lastPower = $power;
                    $buffer = 0;
                } else {
                    $powers[$this->powers[$part]] = $buffer;
                    $buffer = 0;
                    $lastPower = $this->powers[$part];
                }
            } else {
                throw new LogicException('Unknown Token Found: '.$part);
            }
        }
        if (!empty($buffer)) {
            $powers[1] = $buffer;
        }
        $total = 0;
        foreach ($powers as $power => $sub) {
            $total += $power * $sub;
        }
        if ($isNegative) {
            $total *= -1;
        }
        return $total;
    }

    protected function prepare($string) {
        $string = preg_replace('#(\s+|-|\band\b)#i', ' ', $string);
        $string = mb_convert_case($string, MB_CASE_LOWER);
        return $string;
    }

}
$wordToNumber = new WordToNumber();
?>