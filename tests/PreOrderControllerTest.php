<?php
namespace Tests\Unit;

use Peyas\PreOrderForm\Http\Resources\PreOrderResource;
use Peyas\PreOrderForm\Services\RecaptchaService;
use Tests\TestCase;
use Peyas\PreOrderForm\Http\Controllers\PreOrderController;
use Peyas\PreOrderForm\Services\PreOrderService;
use Peyas\PreOrderForm\Http\Requests\PreOrderRequest;
use Peyas\PreOrderForm\Models\PreOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request; // Make sure this is imported


class PreOrderControllerTest extends TestCase
{
    protected $preOrderService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->preOrderService = \Mockery::mock(PreOrderService::class);
        $this->controller = new PreOrderController($this->preOrderService);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
    public function test_index_returns_pre_orders()
    {
        // Arrange
        $request = new Request();
        $preOrders = collect([
            new PreOrder(['id' => 1]), // Provide any necessary attributes
            new PreOrder(['id' => 2]),
        ]);

        // Mock the service method
        $this->preOrderService->shouldReceive('index')->once()->with($request)->andReturn($preOrders);

        // Act
        $resourceCollection = $this->controller->index($request);

        // Assert
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $resourceCollection);

        // Now we can get the data from the resource collection and compare
        $this->assertEquals(
            PreOrderResource::collection($preOrders)->response()->getData(true),
            $resourceCollection->response()->getData(true)
        );
    }
    public function test_store_creates_preorder_successfully()
    {
        // Arrange
        $request = \Mockery::mock(PreOrderRequest::class);
        $validatedData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'product_id' => 1,
            'recaptcha' => 'some_recaptcha_token', // Ensure recaptcha key is included
        ];

        // Mock the request validation
        $request->shouldReceive('validated')->once()->andReturn($validatedData);
        // Mock the request IP
        $request->shouldReceive('ip')->once()->andReturn('192.168.1.1');

        // Mock the RecaptchaService
        $recaptchaService = \Mockery::mock(RecaptchaService::class);
        $recaptchaService->shouldReceive('recaptchaValidate')
            ->once()
            ->with($validatedData, '192.168.1.1')
            ->andReturn(true);

        // Use a partial mock to replace the `new` instantiation within the controller
        \Mockery::mock(RecaptchaService::class)->shouldReceive('recaptchaValidate')->andReturnUsing(function ($data, $ip) use ($recaptchaService) {
            return $recaptchaService->recaptchaValidate($data, $ip);
        });

        // Mock the service and expect it to be called once
        $this->preOrderService->shouldReceive('store')->once()->with($validatedData);

        // Act
        $response = $this->controller->store($request);
//        dd($response);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Your Pre Order Store Successfully']),
            $response->getContent()
        );

        // Clean up Mockery
        \Mockery::close();
    }


    public function test_show_returns_pre_order()
    {
        // Arrange
        $preOrder = new PreOrder(['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com']);

        // Act
        $response = $this->controller->show($preOrder);

        // Assert
        $this->assertInstanceOf(PreOrderResource::class, $response);
        $this->assertEquals($preOrder->id, $response->resource->id);
        $this->assertEquals($preOrder->name, $response->resource->name);
        $this->assertEquals($preOrder->email, $response->resource->email);
    }

    public function test_destroy_deletes_pre_order()
    {
        // Arrange
        $preOrder = new PreOrder(['id' => 1]); // Create a mock PreOrder instance

        // Mock the delete method
        $this->preOrderService->shouldReceive('delete')->once()->with($preOrder);

        // Act
        $response = $this->controller->destroy($preOrder);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Pre Order Deleted Successfully']),
            $response->getContent()
        );
    }
}
