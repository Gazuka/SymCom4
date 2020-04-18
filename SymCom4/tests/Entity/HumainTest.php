<?php

namespace App\Tests\Entity;

use App\Entity\Humain;
use PHPUnit\Framework\TestCase;

class HumainTest extends TestCase
{
    public function testGetSetNom()
    {
        $valeur = 'Carion';
        $humain = new Humain();
        $humain->setNom($valeur);
        $this->assertEquals($valeur, $humain->getNom());
    }

    public function testGetSetPrenom()
    {
        $valeur = 'Jérôme';
        $humain = new Humain();
        $humain->setPrenom($valeur);
        $this->assertEquals($valeur, $humain->getPrenom());
    }
}