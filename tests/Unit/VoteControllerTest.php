<?php 
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Question;
use App\Models\Option;
use App\Models\Vote;
use App\Services\TranslatorService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class VoteControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // Perform necessary setup, such as migrations or other prerequisites
        // For example:
        // $this->artisan('migrate');
    }

    // Test for the 'index' method
    public function testIndex()
    {
        // Mock authenticated user (replace with your authentication logic if required)
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create sample questions, options, votes (customize as needed)
        // ...

        // Make a GET request to the 'index' method
        $response = $this->get(route('vote.index'));

        $response->assertStatus(200);
        $response->assertViewIs('vote.index');
        $response->assertViewHas('questions');
    }

    // Test for the 'store' method
    public function testStore()
    {
        $user = User::factory()->create();
        $this->actingAs($user);


    }

    // Test for the 'show' method
    public function testShow()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    // Test for the 'results' method
    public function testResults()
    {
        // Create a sample question, options, votes (customize as needed)
        // ...

        // Make a GET request to the 'results' method
        // ...

        // Assert the response status code and content
        // ...

        // Add assertions for expected view data or content
        // ...
    }

    // Test for the 'search' method
    public function testSearch()
    {
        $this->actingAs(User::find(1));
        $response = $this->get(route('search.questions'), ['query' => 'What']);

        // Assert the response status code
        $response->assertStatus(200);

        // Assert the structure of the JSON response
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'description',
                'image_src',
            ],
        ]);

        $response->assertJsonIsArray();
    }
}
