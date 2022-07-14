<?php

namespace HBM\MessengerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HBMMessengerBundle extends Bundle {

  public function getPath(): string {
    return \dirname(__DIR__);
  }

}
