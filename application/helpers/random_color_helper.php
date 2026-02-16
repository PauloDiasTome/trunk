<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Colors\RandomColor;

function RandomColor()
{
   return RandomColor::one();
}
