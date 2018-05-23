<?php
/**
 * Created by PhpStorm.
 * User: phill
 * Date: 15.05.2018
 * Time: 04:49
 */

namespace PUS\Util\Random;

use Monolog\Logger;

class RandomNamer
{
    private $seed;

    public function __construct(int $seed) {
        $this->seed = $seed;
    }

    public function create() {
        mt_srand($this->seed);
        $rand = (string)mt_rand();

        $letters = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j");
        $shift = true;
        $out = "";

        for ($i = 0; $i < 5; ++$i) {
            if ($shift)
                $out .= $letters[$rand[$i]];
            else
                $out.= $rand[$i];
            $shift = !$shift;
        }

        return $out;
    }
}