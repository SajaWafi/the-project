use App\Models\User;
use App\Models\ParentProfile;
use App\Models\Child;
use Illuminate\Support\Facades\Hash;

public function run()
{
    // إنشاء user
    $user = User::create([
        'first_name' => 'Ali',
        'last_name' => 'Hasan',
        'email' => 'ali@test.com',
        'password' => Hash::make('123456'),
        'role' => 'parent',
    ]);

    // إنشاء parent profile
    $parent = ParentProfile::create([
        'user_id' => $user->id,
        'relation_to_child' => 'Father',
    ]);

    // إنشاء child
    Child::create([
        'parent_id' => $parent->id,
        'name' => 'Ahmed',
    ]);
}