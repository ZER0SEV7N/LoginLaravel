<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialService{
    
    public function findOrCreate(SocialiteUser $socialiteUser, string $provider): User{
        
        $user = User::where('email', $socialiteUser->getEmail())->first();

        $providerIdField = "{$provider}_id";
        
        if(!$user){
            $user = User::create([
                'name' => $socialiteUser->getName() ?? $socialiteUser->getNickname(),
                'email' => $socialiteUser->getEmail(),
                $providerIdField => $socialiteUser->getId(),
                'email_verified_at' => now(),
                'password' => null,
            ]);
        }else{
            if(!$user->$providerIdField)
                $user->update([$providerIdField => $socialiteUser->getId()]);
        }

        return $user;
    }

    public function profileIncomplete(User $user): bool{
        return empty($user->name) 
        || empty($user->lastname) 
        || empty($user->username) 
        || empty($user->phone) 
        || empty($user->document);
    }
}