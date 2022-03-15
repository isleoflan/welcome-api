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
            'errorCode' => 880001,
        ]
    ]);

    try {
        $ticket = new Ticket($input['ticketCode']);
    } catch (\IOL\Generic\v1\Exceptions\IOLException) {
        $response->addError(880101)->render();
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
            'errorCode' => 880001,
        ],
        [
            'name' => 'identification',
            'types' => ['boolean'],
            'required' => true,
            'errorCode' => 880002,
        ],
        [
            'name' => 'badgeSerialNumber',
            'types' => ['string'],
            'required' => true,
            'errorCode' => 880003,
        ],
        [
            'name' => 'merch',
            'types' => ['boolean'],
            'required' => true,
            'errorCode' => 88000,
        ]
    ]);

    try {
        $ticket = new Ticket($input['ticketCode']);
    } catch (\IOL\Generic\v1\Exceptions\IOLException) {
        $response->addError(880101)->render();
    }

    if (!$input['identification']) {
        $response->addError(880102)->render();
    }
    if (!$input['merch']) {
        $response->addError(880103)->render();
    }

    $card = new Card();
    $card->register($input['badgeSerialNumber'], $ticket->getUser());
}