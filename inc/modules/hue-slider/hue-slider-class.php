<?php
class HueSlider
{
    private $hue_slider;
    //Tries to get a private property that belongs to the parent
    private function slider_setup($hue_slider)
    {
        return "beep! I am a <i>" . $this -> model . "</i><br />";
    }
    public function __construct($hue_slider)
    {
        $this->hue_slider = $hue_slider;
    }
    public function get_HueSlider()
    {
        return $this->hue_slider;
    }
    public function set_HueSlider($hue_slider)
    {
        $this->hue_slider = $hue_slider;
    }
}