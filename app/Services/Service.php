<?php

namespace App\Services;

use Manpro\Manpro;

class Service{

    protected $manpro;
    public function __construct()
    {
        $this->manpro = new Manpro();
    }

}