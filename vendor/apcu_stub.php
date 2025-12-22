<?php
// APCu stub functions for environments without the APCu extension.
// These are defined only if the real functions are not available to avoid collisions.
if (!function_exists('apcu_fetch')) {
  function apcu_fetch($key, &$success = null)
  {
    $success = false;
    return false;
  }
}

if (!function_exists('apcu_add')) {
  function apcu_add($key, $var)
  {
    return false;
  }
}
