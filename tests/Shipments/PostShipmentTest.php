<?php

declare(strict_types=1);

namespace dqfan2012\Sellbrite\Test\Shipments;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use dqfan2012\Sellbrite\Shipments\PostShipment;

class PostShipmentTest extends TestCase
{
    public function testPostShipmentSuccessfullyCreateShipment()
    {
        // Get the stored credentials
        $accountToken = 'am2902ngt3Nn';
        $secretKey    = 'happy28bananas';

        // Create a mock client object
        $mockClient = \Mockery::mock(ClientInterface::class);

        // The mock client should receive a request call and it should return at PSR-7 Response object
        // cotaining JSON
        $mockClient->shouldReceive('request')->andReturns(
            new Response(
                200,
                [ 'Content-Type' => 'application/json' ],
                '{ "body": "Shipment created!" }'
            )
        );

        // Shipment Array
        $shipmentArray = [
            'shipment' => [
                'sb_order_sequence' => 8888,
                "tracking_number" => "123n1sd123d",
                "carrier_name" => "USPS",
                "warehouse_uuid" => "68e5379d-0361-434a-8136-14bd3b7762f9",
                "shipping_method" => "1 day",
                "items" => [
                    [
                        "sku" => "NTLLGNT-CDB101230F",
                        "quantity" => 2
                    ],
                    [
                        "sku" => "e90123e0a3",
                        "quantity" => 3
                    ]
                ]
            ]
        ];

        // Instantiate a new PutInventory API Object
        $postShipment = new PostShipment($accountToken, $secretKey, $mockClient);

        // Send the request
        $responseStr = $postShipment->sendRequest($shipmentArray);

        // Assert the shipment has been successfully created
        $this->assertEquals($responseStr, 'Shipment created!');
    }

    public function testPostShipmentApiBadCredentialsRequestShouldReturnAnException()
    {
        // Get the stored credentials
        $accountToken = '';
        $secretKey    = '';

        // Create a mock client object
        $mockClient = \Mockery::mock(ClientInterface::class);

        // The mock client should receive a request call and it should return at PSR-7 Response object
        // cotaining an error
        $mockClient->shouldReceive('request')->andReturns(
            new Response(
                401,
                [ 'Content-Type' => 'text/html' ],
                "HTTP Basic: Access denied."
            )
        );

        // Instantiate a new PostShipment API Object
        $postShipment = new PostShipment($accountToken, $secretKey, $mockClient);

        // Expect an exception from the request
        $this->expectException(\Exception::class);

        // Send the request and store the response
        $response = $postShipment->sendRequest();
    } // End public function testPostShipmentApiBadCredentialsRequestShouldReturnAnException

    public function testPostShipmentApiBadRequestError()
    {
        // Get the stored credentials
        $accountToken = 'am2902ngt3Nn';
        $secretKey    = 'happy28bananas';

        // Create a mock client object
        $mockClient = \Mockery::mock(ClientInterface::class);

        // The mock client should receive a request call and it should return at PSR-7 Response object
        // cotaining JSON
        $mockClient->shouldReceive('request')->andReturns(
            new Response(
                400,
                [ 'Content-Type' => 'application/json' ],
                '{ "error": "Please include order items to be shipped" }'
            )
        );

        // Shipment Array
        $shipmentArray = [
            'shipment' => [
                'sb_order_sequence' => 8888,
                "tracking_number" => "123n1sd123d",
                "carrier_name" => "USPS",
                "warehouse_uuid" => "68e5379d-0361-434a-8136-14bd3b7762f9",
                "shipping_method" => "1 day",
                "items" => []
            ]
        ];

        // Instantiate a new PutInventory API Object
        $postShipment = new PostShipment($accountToken, $secretKey, $mockClient);

        // Expect an exception from the request
        $this->expectException(\Exception::class);

        // Send the request
        $response = $postShipment->sendRequest($shipmentArray);
    } // End public function testPostShipmentApiBadRequestError

    public function testPostShipmentApiOrderNotFoundError()
    {
        // Get the stored credentials
        $accountToken = 'am2902ngt3Nn';
        $secretKey    = 'happy28bananas';

        // Create a mock client object
        $mockClient = \Mockery::mock(ClientInterface::class);

        // The mock client should receive a request call and it should return at PSR-7 Response object
        // cotaining JSON
        $mockClient->shouldReceive('request')->andReturns(
            new Response(
                404,
                [ 'Content-Type' => 'application/json' ],
                '{ "error": "Order is not found." }'
            )
        );

        // Shipment Array
        $shipmentArray = [
            'shipment' => [
                'sb_order_sequence' => 8888,
                "tracking_number" => "123n1sd123d",
                "carrier_name" => "USPS",
                "warehouse_uuid" => "68e5379d-0361-434a-8136-14bd3b7762f9",
                "shipping_method" => "1 day",
                "items" => [
                    [
                        "sku" => "NTLLGNT-CDB101230F",
                        "quantity" => 2
                    ],
                    [
                        "sku" => "e90123e0a3",
                        "quantity" => 3
                    ]
                ]
            ]
        ];

        // Instantiate a new PutInventory API Object
        $postShipment = new PostShipment($accountToken, $secretKey, $mockClient);

        // Expect an exception from the request
        $this->expectException(\Exception::class);

        // Send the request
        $response = $postShipment->sendRequest($shipmentArray);
    } // End public function testPostShipmentApiOrderNotFoundError

    public function testPostShipmentApiUnprocessableEntityError()
    {
        // Get the stored credentials
        $accountToken = 'am2902ngt3Nn';
        $secretKey    = 'happy28bananas';

        // Create a mock client object
        $mockClient = \Mockery::mock(ClientInterface::class);

        // The mock client should receive a request call and it should return at PSR-7 Response object
        // cotaining JSON
        $mockClient->shouldReceive('request')->andReturns(
            new Response(
                422,
                [ 'Content-Type' => 'application/json' ],
                '{ "error": "Only open orders can be shipped" }'
            )
        );

        // Shipment Array
        $shipmentArray = [
            'shipment' => [
                'sb_order_sequence' => 8888,
                "tracking_number" => "123n1sd123d",
                "carrier_name" => "USPS",
                "warehouse_uuid" => "68e5379d-0361-434a-8136-14bd3b7762f9",
                "shipping_method" => "1 day",
                "items" => [
                    [
                        "sku" => "NTLLGNT-CDB101230F",
                        "quantity" => 2
                    ],
                    [
                        "sku" => "e90123e0a3",
                        "quantity" => 3
                    ]
                ]
            ]
        ];

        // Instantiate a new PutInventory API Object
        $postShipment = new PostShipment($accountToken, $secretKey, $mockClient);

        // Expect an exception from the request
        $this->expectException(\Exception::class);

        // Send the request
        $response = $postShipment->sendRequest($shipmentArray);
    } // End public function testPostShipmentApiUnprocessableEntityError

    public function testPostShipmentApiDefaultError()
    {
        // Get the stored credentials
        $accountToken = 'am2902ngt3Nn';
        $secretKey    = 'happy28bananas';

        // Create a mock client object
        $mockClient = \Mockery::mock(ClientInterface::class);

        // The mock client should receive a request call and it should return at PSR-7 Response object
        // cotaining JSON
        $mockClient->shouldReceive('request')->andReturns(
            new Response(
                500,
                [ 'Content-Type' => 'application/json' ],
                '{ "error": "Please include order items to be shipped" }'
            )
        );

        // Shipment Array
        $shipmentArray = [
            'shipment' => [
                'sb_order_sequence' => 8888,
                "tracking_number" => "123n1sd123d",
                "carrier_name" => "USPS",
                "warehouse_uuid" => "68e5379d-0361-434a-8136-14bd3b7762f9",
                "shipping_method" => "1 day",
                "items" => []
            ]
        ];

        // Instantiate a new PutInventory API Object
        $postShipment = new PostShipment($accountToken, $secretKey, $mockClient);

        // Expect an exception from the request
        $this->expectException(\Exception::class);

        // Send the request
        $response = $postShipment->sendRequest($shipmentArray);
    } // End public function testPostShipmentApiDefaultError
} // End class PostShipmentTest
