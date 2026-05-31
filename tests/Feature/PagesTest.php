use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('front pages return a successful response', function () {
    $routes = ['home', 'store', 'product', 'checkout', 'blank'];

    foreach ($routes as $routeName) {
        $response = $this->get(route($routeName));
        $response->assertStatus(200);
    }
});

test('admin page returns a successful response for admin user', function () {
    $adminRole = \App\Models\Role::create(['name' => 'admin', 'description' => 'Admin Role']);
    $admin = \App\Models\User::factory()->create();
    $admin->roles()->attach($adminRole);

    $response = $this->actingAs($admin)->get(route('admin.home'));
    $response->assertStatus(200);
});
