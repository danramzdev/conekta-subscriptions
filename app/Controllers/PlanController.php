<?php

namespace App\Controllers;


class PlanController extends BaseController
{
    public function index()
    {
        return $this->renderHTML('plan.twig');
    }
}