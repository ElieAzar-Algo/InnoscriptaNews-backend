<?php

namespace App\Services;
use App\Models\Preference;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function createUser(array $data):User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function attachPreferences(Request $request)
    {
        $user = Auth::user();
        $preferences = $request->json('preferences');
        Log::info("007 preferences", $preferences);
        // dd();

        foreach ($preferences as $preferenceData) {
            $source = $preferenceData['source'] ?? 'all';
            $author = $preferenceData['author'] ?? 'all';
            $category = $preferenceData['category'] ?? 'all';
    
            $preference = Preference::create([
                'source' => $source,
                'author' => $author,
                'category' => $category,
            ]);
            $user->preferences()->attach($preference->id);
        }
    
        return $user;
    }
        
}

