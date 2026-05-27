$u = App\Models\User::firstOrNew(['email' => 'admin@admin.com']);
$u->name = 'Головний Адмін';
$u->username = 'admin';
$u->password = Hash::make('admin');
$u->role = 'admin';
$u->save();
echo "Admin updated successfully.\n";
