<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 19/02/2019
 * Time: 01:20 PM
 */

namespace App\Controllers;

use Zend\Diactoros\Response\HtmlResponse;


class BaseController
{
    protected $templateEngine;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('../views');
        $this->templateEngine = new \Twig\Environment($loader, array(
            'debug' => true,
            'cache' => false
        ));
    }

    public function renderHTML($fileName, $data = [])
    {
        return new HtmlResponse($this->templateEngine->render($fileName, $data));
    }
}