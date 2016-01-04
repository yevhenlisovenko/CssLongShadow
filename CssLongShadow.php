<?php

/**
 * Created by PhpStorm.
 * User: Yevhen Lisovenko @iocheck
 * Date: 04/01/16
 * Time: 14:38
 *
 *
 * The code is generate the shadow long css for text and/or box.
 *
 * USAGE:
 *
 *
 *<style>
 *
 *.shadow{
 *      <?=CssLongShadow::get('text', "#ff5350", 55, true, false, 'right');?>
 *}
 *
 *</style>
 *
 *
 */


class CssLongShadow
{


    public static function get($type, $color, $length, $fadeout = true, $skew = false, $direction = "right"){

        $color = self::adjustBrightness($color, -25);

        $shadow = '';

        if ($skew == false || $type == "text"){
            if ($direction == "right") {
                for ($i=0;$i<=$length - 1;$i++) {
                    $shadow = $shadow . $i . 'px ' . $i . 'px 0 ' . $color . ',';
                }
            }
            if($direction == "left"){
                for ($i=0;$i<=$length - 1;$i++) {
                    $shadow = $shadow . $i * -1 . 'px ' . $i . 'px 0 ' . $color . ',';
                }
            }
        }
        if( $fadeout == true) {
            for($i=1;$i<=$length - 1;$i++) {
                if( $type == "text" || $skew == false ){
                    if ($direction == "right"){
                        $shadow = $shadow . $i . 'px ' . $i . 'px 0 ' .       self::rgba($color, 1 - $i / $length) . ',';
                    }
                    if( $direction == "left"){
                        $shadow =  $shadow . $i * -1 . 'px ' . $i . 'px 0 ' .       self::rgba($color, 1 - $i / $length) . ',';
                    }
                }
                if ($type == "box" && $skew == true) {
                    if ($direction == "right"){
                        $shadow = $shadow . $i . 'px ' . $i . 'px 0 ' . $i * .2 . 'px ' . self::rgba($color, 1 - $i / $length) . ',';
                    }
                    if ($direction == "left") {
                        $shadow = $shadow . $i * -1 . 'px ' . $i . 'px 0 ' . $i * .2 . 'px ' . self::rgba($color, 1 - $i / $length) . ',';
                    }
                }
            }

            $shadow = $shadow . $length . 'px ' . $length . 'px 0 ' . self::rgba($color, 0);
        }
        if ($fadeout == false) {
            if($skew == true && $type == "box" ){
                for( $i = 0; $i<= $length - 1;$i++) {
                    $shadow = $shadow . $i . 'px ' . $i . 'px 0 ' . $i * .1 . 'px ' . $color . ',';
                }
            }
            $shadow = $shadow . $length . 'px ' . $length . 'px 0 rgba(0,0,0,0)';
        }
        #$shadow = unquote($shadow);
        if ($type == 'box') { return "box-shadow: ".$shadow.";-webkit-box-shadow: ".$shadow.";-moz-box-shadow: ".$shadow.";"; }
        if ($type == 'text'){ return "text-shadow: ".$shadow.";-webkit-text-shadow: ".$shadow.";-moz-text-shadow: ".$shadow.";"; }

    }

    private static function adjustBrightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0,min(255,$color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }

    private static function rgba($color, $opacity = false) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if(empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
    }


}
