<?php

declare(strict_types=1);

use IOL\Generic\v1\BitMasks\RequestMethod;
use IOL\Generic\v1\Request\APIResponse;
use IOL\Welcome\v1\Entity\Card;
use IOL\Welcome\v1\Entity\Ticket;

$response = APIResponse::getInstance();

$response->setAllowedRequestMethods(
    new RequestMethod(RequestMethod::GET)
);
$response->needsAuth(true);

if (APIResponse::getRequestMethod() === RequestMethod::GET) {
    $input = $response->getRequestData([
        [
            'name' => 'ticketCode',
            'types' => ['string'],
            'required' => true,
            'errorCode' => 105101,
        ]
    ]);

    try {
        $ticket = new Ticket($input['ticketCode']);
    } catch (\IOL\Generic\v1\Exceptions\IOLException) {
        $response->addError(0)->render();
    }

    $response->addData('user', $ticket->getUser()->serialize());
    $response->addData('merch', $ticket->getOrder()->getMerch());
}


if (APIResponse::getRequestMethod() === RequestMethod::POST) {
    $input = $response->getRequestData([
        [
            'name' => 'ticketCode',
            'types' => ['string'],
            'required' => true,
            'errorCode' => 105101,
        ],
        [
            'name' => 'identification',
            'types' => ['boolean'],
            'required' => true,
            'errorCode' => 105101,
        ],
        [
            'name' => 'badgeSerialNumber',
            'types' => ['string'],
            'required' => true,
            'errorCode' => 105101,
        ],
        [
            'name' => 'merch',
            'types' => ['boolean'],
            'required' => true,
            'errorCode' => 105101,
        ]
    ]);

    try {
        $ticket = new Ticket($input['ticketCode']);
    } catch (\IOL\Generic\v1\Exceptions\IOLException) {
        $response->addError(0)->render();
    }

    if (!$input['identification']) {
        $response->addError(0)->render();
    }
    if (!$input['merch']) {
        $response->addError(0)->render();
    }

    $card = new Card();
    $card->register($input['badgeSerialNumber'], $ticket->getUser());
}