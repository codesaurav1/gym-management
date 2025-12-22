<?php

namespace Composer\Semver;

class VersionParser
{
  /**
   * Minimal stub that returns an object with a `matches` method.
   * This is intentionally minimal to satisfy static analysis and avoid runtime errors
   * in environments where composer/semver isn't installed.
   */
  public function parseConstraints($constraint)
  {
    return new class ($constraint) {
      private $constraint;
      public function __construct($constraint)
      {
        $this->constraint = $constraint;
      }
      public function matches($other)
      {
        // Conservative default: only return true for identical constraint strings
        if (is_object($other) && property_exists($other, 'constraint')) {
          return $this->constraint === $other->constraint;
        }
        return false;
      }
    };
  }
}
