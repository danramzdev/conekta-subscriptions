<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;

class PlanController extends BaseController
{
    private $apiKey = "key_GbgD9zrqbVxbsyyy6J8CaA";
    private $apiVersion = "2.0.0";
    protected $error;

    public function checkout(ServerRequest $request)
    {
        \Conekta\Conekta::setApiKey($this->apiKey);
        \Conekta\Conekta::setApiVersion($this->apiVersion);

        $params = $request->getQueryParams();

        return $this->renderHTML('checkout.twig', [
           'plan' => $params['id']
        ]);
    }

    public function payment(ServerRequest $request)
    {
        \Conekta\Conekta::setApiKey($this->apiKey);
        \Conekta\Conekta::setApiVersion($this->apiVersion);

        error_reporting(E_ALL ^ E_WARNING);

        $data = $request->getParsedBody();

        $planId = $data['plan'];
        $plan = \Conekta\Plan::find($planId);

        try {
            $customer = \Conekta\Customer::create(
                array(
                    "name" => $data['first-name'] . ' ' . $data['last-name'],
                    "email" => $data['email'],
                    "phone" => $data['phone'],
                    "payment_sources" => array(
                        array(
                            "type" => "card",
                            "token_id" => $data['conektaTokenId']
                        )
                    )//payment_sources
                )//customer
            );
        } catch (\Conekta\ProccessingError $error){
            $this->error = $error->getMesage();
        } catch (\Conekta\ParameterValidationError $error){
            $this->error = $error->getMessage();
        } catch (\Conekta\Handler $error){
            $this->error = $error->getMessage();
        }

        $subscription = $customer->createSubscription(
            array(
                'plan' => 'plan-semanal'
            )
        );

        return $this->renderHTML('plan.twig', [
            'error' => $this->error
        ]);
    }
}