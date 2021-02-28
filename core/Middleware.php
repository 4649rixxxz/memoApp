<?php

namespace app\core;

abstract class Middleware
{
  abstract function guard($class);
}