<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_notes()
    {
        Note::factory()->count(3)->create();
        $response = $this->getJson('/api/notes');
        $response->assertStatus(200)->assertJsonCount(3);
    }

    public function test_create_note()
    {
        $data = ['title' => 'Test Note', 'content' => 'Test Content'];
        $response = $this->postJson('/api/notes', $data);
        $response->assertStatus(201)->assertJson($data);
    }

    public function test_create_note_validation_fails()
    {
        $response = $this->postJson('/api/notes', []);
        $response->assertStatus(422);
    }

    public function test_show_note()
    {
        $note = Note::factory()->create();
        $response = $this->getJson("/api/notes/{$note->id}");
        $response->assertStatus(200)->assertJson([
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
        ]);
    }

    public function test_show_note_not_found()
    {
        $response = $this->getJson('/api/notes/999');
        $response->assertStatus(404)->assertJson(['error' => 'Note not found']);
    }

    public function test_update_note()
    {
        $note = Note::factory()->create();
        $updatedData = ['title' => 'Updated Title', 'content' => 'Updated Content'];
        $response = $this->putJson("/api/notes/{$note->id}", $updatedData);
        $response->assertStatus(200)->assertJson($updatedData);
        $this->assertDatabaseHas('notes', $updatedData);
    }

    public function test_update_note_validation_fails()
    {
        $note = Note::factory()->create();
        $response = $this->putJson("/api/notes/{$note->id}", []);
        $response->assertStatus(422);
    }

    public function test_update_note_not_found()
    {
        $response = $this->putJson('/api/notes/999', ['title' => 'Test', 'content' => 'Test']);
        $response->assertStatus(404)->assertJson(['error' => 'Note not found']);
    }

    public function test_delete_note()
    {
        $note = Note::factory()->create();
        $response = $this->deleteJson("/api/notes/{$note->id}");
        $response->assertStatus(200)->assertJson(['message' => 'Note deleted']);
        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    public function test_delete_note_not_found()
    {
        $response = $this->deleteJson('/api/notes/999');
        $response->assertStatus(404)->assertJson(['error' => 'Note not found']);
    }
}
