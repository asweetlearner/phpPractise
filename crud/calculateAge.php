<?php
/**
 * Created by IntelliJ IDEA.
 * User: 15863
 * Date: 2018/12/19
 * Time: 21:38
 */
function age($birthday)
{
    $d1 = strtotime($birthday);
    $yBirth = date('Y', $d1);
    $mBirth = date('m', $d1);
    $dBirth = date('d', $d1);

    $yNow = date('Y', time());
    $mNow = date('m', time());
    $dNow = date('d', time());
    if ($yBirth > $yNow) return '';
    else {
        $age = $yNow - $yBirth;
        if ($mBirth < $mNow) {
            $age = $age - 1;
            return $age;
        }
        if ($mBirth == $mNow) {
            if ($dBirth <= $dNow) return $age;
            else {
                $age = $age - 1;
                return $age;
            }
        }
    }
return $age;
}


//$age = $now - $birth;
//echo $age;