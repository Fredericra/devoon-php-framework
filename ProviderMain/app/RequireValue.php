<?php

namespace ProviderMain\app;


class RequireValue
{
    public static function Style()
    {
      $style = __DIR__."/error.css";
      echo $style;
      echo "<link rel='stylesheet' href='{$style}'>";
    }
}
  