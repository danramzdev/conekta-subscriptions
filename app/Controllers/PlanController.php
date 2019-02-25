<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;

class PlanController extends BaseController
{
    public function checkout(ServerRequest $request)
    {
        \Conekta\Conekta::setApiKey("key_GbgD9zrqbVxbsyyy6J8CaA");
        \Conekta\Conekta::setApiVersion("2.0.0");

        $params = $request->getQueryParams();

        return $this->renderHTML('checkout.twig', [
           'plan' => $params['id']
        ]);
    }

    public function payment(ServerRequest $request)
    {
        \Conekta\Conekta::setApiKey("key_GbgD9zrqbVxbsyyy6J8CaA");
        \Conekta\Conekta::setApiVersion("2.0.0");

        $data = $request->getParsedBody();

        $planId = $data['plan'];
        $plan = \Conekta\Plan::find($planId);

        try {
            $customer = \Conekta\Customer::create(
                array(
                    "name" => $data['first-name'] . ' ' . $data['last-name'],
                    "email" => $data['email'],
                    "phone" => $data['phone'],
                    "metadata" => array("reference" => "12987324097", "random_key" => random_int(1, 1000000)),
                    "payment_sources" => array(
                        array(
                            "type" => "card",
                            "token_id" => $data['conektaTokenId']
                        )
                    )//payment_sources
                )//customer
            );
        } catch (\Conekta\ProccessingError $error){
            echo $error->getMesage();
        } catch (\Conekta\ParameterValidationError $error){
            echo $error->getMessage();
        } catch (\Conekta\Handler $error){
            echo $error->getMessage();
        }


        return $this->renderHTML('plan.twig');
    }
}