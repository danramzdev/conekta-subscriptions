<?php

require_once 'vendor/autoload.php';

\Conekta\Conekta::setApiKey("key_GbgD9zrqbVxbsyyy6J8CaA");
\Conekta\Conekta::setApiVersion("2.0.0");

$token_id = $_POST['conektaTokenId'];

var_dump($token_id);

try {
    $customer = \Conekta\Customer::create(
        array(
            "name" => "Daniel Ramirez",
            "email" => "dan@conekta.com",
            "phone" => "+526969696969",
            "metadata" => array("reference" => "12987324097", "random_key" => "random value"),
            "payment_sources" => array(
                array(
                    "type" => "card",
                    "token_id" => $token_id
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

$plan = \Conekta\Plan::create(
    array(
        "id" => "plan-semanal",
        "name" => "Plan que se cobra cada semana",
        "amount" => 10000,
        "currency" => "MXN",
        "interval" => "week"
    )//plan
);

$subscription = $customer->createSubscription(
    array(
        'plan' => 'plan-semanal'
    )
);