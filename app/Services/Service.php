<?php
namespace App\Services;
use Manpro\Manpro;

class Service{

    public function __construct()
    {
        $this->manpro = new Manpro();
    }

}