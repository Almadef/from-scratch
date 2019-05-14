<?php

namespace Application\Core;

/**
 * Class Controller
 */
class Controller
{

    /**
     * @var View
     */
    protected $view;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = new View();
    }
}
