<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Room;

class RoomApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_room()
    {
        $room = Room::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/admin/rooms', $room
        );

        $this->assertApiResponse($room);
    }

    /**
     * @test
     */
    public function test_read_room()
    {
        $room = Room::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/admin/rooms/'.$room->id
        );

        $this->assertApiResponse($room->toArray());
    }

    /**
     * @test
     */
    public function test_update_room()
    {
        $room = Room::factory()->create();
        $editedRoom = Room::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/admin/rooms/'.$room->id,
            $editedRoom
        );

        $this->assertApiResponse($editedRoom);
    }

    /**
     * @test
     */
    public function test_delete_room()
    {
        $room = Room::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/admin/rooms/'.$room->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/admin/rooms/'.$room->id
        );

        $this->response->assertStatus(404);
    }
}
