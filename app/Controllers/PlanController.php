<?php

namespace App\Controllers;

use Zend\Diactoros\ServerRequest;
use App\Models\Subscribers;

class PlanController extends BaseController
{
    private $apiKey;
    private $apiVersion;
    protected $error;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = getenv('API_KEY');
        $this->apiVersion = getenv('API_VERSION');
    }

    public function checkout(ServerRequest $request)
    {
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
        $email = $data['email'];

        $subscriber = Subscribers::where('email', $email)->first() ?? null;

        if ($subscriber) {
            try {
                $customer = \Conekta\Customer::find($subscriber->customer_id);
            } catch (\Conekta\ProccessingError $error){
                $this->error = $error->getMesage();
            } catch (\Conekta\ParameterValidationError $error){
                $this->error = $error->getMessage();
            } catch (\Conekta\Handler $error){
                $this->error = $error->getMessage();
            }
        } else {
            $subscriber = new Subscribers();

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
        }

        $subscription = $customer->createSubscription(
            array(
                'plan' => $planId
            )
        );

        $status = ($customer->subscription['status'] === 'active') ? 1 : 0;

        $subscriber->name = $data['first-name'] . ' ' . $data['last-name'];
        $subscriber->email = $email;
        $subscriber->phone = $data['phone'];
        $subscriber->customer_id = $customer->id;
        $subscriber->plan_id = $planId;
        $subscriber->subscription_id = $customer->subscription['id'];
        $subscriber->status = $status;
        $subscriber->save();

        return $this->renderHTML('plan.twig', [
            'error' => $this->error
        ]);
    }
}