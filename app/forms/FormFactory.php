<?php declare(strict_types = 1);

namespace App;

class FormFactory
{

    use \Nette\SmartObject;

    public function create()
    {
        return new \Nette\Application\UI\Form;
    }

}
