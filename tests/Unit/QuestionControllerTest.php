<?php

namespace Tests\Unit;

use Database\Factories\QuestionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Storage;
use App\Models\User;
use App\Models\Image;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;
use Auth;
use App\Http\Controllers\QuestionController;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
class QuestionControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Perform migrations, seeding, or any initial setup
    }

    public function testIndex()
    { 
         $this->actingAs(User::find(23));
        $this->withoutExceptionHandling();
       $this->get('/questions')->assertViewHas('questions');

     
    }

    public function testStore()
    {

        $data = Question::factory()->createOne();
        $image = Image::factory()->createOne();
        $data->image_id = $image->id;
        $data->save();
        $this->actingAs(User::find(23));

        // Assert the question is stored in the database
        $this->assertDatabaseHas('questions', [
            'title' => 'Test Title',
            'description' => 'Test Description',
            'start_at' => now(),
            'end_at' => now()->modify("+" . 5 . " days"), // Assuming it's nullable
        ]);
        $this->assertFileExists($image->filename);
    }


    public function testLock()
    {
        // Create a sample question
        $data = Question::factory()->createOne();
        $image = Image::factory()->createOne();
        $data->image_id = $image->id;
        $data->save();
        $this->actingAs(User::find(23));

        // Simulate the lock request
        $response = $this->post(route('questions.lock'), ['question_id' => $data->id]);

        // Assert that the question's active status is set to false after locking
        $this->assertDatabaseHas('questions', [
            'id' => $data->id,
            'active' => false,
        ]);

        // Assert the response status code or any other response details if needed
        $response->assertStatus(200); // Assuming it returns a status 200 on success
    }
    public function testUnlock()
    {
        // Create a sample question
        $data = Question::factory()->createOne();
        $image = Image::factory()->createOne();
        $data->image_id = $image->id;
        $data->save();
        $this->actingAs(User::find(23));

        // Simulate the lock request
        $response = $this->post(route('questions.unlock'), ['question_id' => $data->id]);
        $this->assertDatabaseHas('questions', [
            'id' => $data->id,
            'active' => true,
        ]);

        // Assert the response status code or any other response details if needed
        $response->assertStatus(200); // Assuming it returns a status 200 on success
    }

    public function testUpdate()
    {
        // Create a sample question and options
        $data = Question::factory()->createOne();
        $image = Image::factory()->createOne();
        $data->image_id = $image->id;
        $this->actingAs(User::find(23));
        $data->title = 'Updated Title';
        $data->description = 'Updated Description';
        $data->save();
        // Assert the question is updated in the database
        $this->assertDatabaseHas('questions', [
            'id' => $data->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ]);
    }
    public function testCreate()
    {
        $this->actingAs(User::find(23));
        $response = $this->get(route('questions.create'));

        $response->assertStatus(200);

        $response->assertViewIs('question.create');
    }
    public function testEdit()
    {
        $this->actingAs(User::find(23));
        
        $question = Question::factory()->createOne();
        // Mock a GET request to the edit method with the question ID
        $response = $this->get(route('questions.edit', ['questionId' => $question->id]));

        $response->assertStatus(200);

        $response->assertViewIs('question.edit');

        $response->assertViewHas('question', function ($viewQuestion) use ($question) {
            return $viewQuestion->id === $question->id;
        });
    }

    public function testDestroy()
    {
        // Create a sample question
        $data = Question::factory()->createOne();
        $image = Image::factory()->createOne();
        $data->image_id = $image->id;
        $this->actingAs(User::find(23));
        $data->save();

        // Mock a DELETE request to delete the question
        $response = $this->delete(route('questions.delete'),['question_id' => $data->id]);

        // Assert the response is JSON indicating successful deletion
        $this->assertEquals('Question deleted successfully',$response['message']);

        // Assert the question is deleted from the database
        $this->assertDatabaseMissing('questions', ['id' => $data->id]);
    }
}
