<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class CustomUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
             array_key_exists('password', $credentials))) {
            return null;
        }

        // The "login_identifier" is the field that can be either email or member_number
        if (isset($credentials['login_identifier'])) {
            $loginIdentifier = $credentials['login_identifier'];
            
            // Check if the login identifier is an email
            if (filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL)) {
                return $this->createModel()->newQuery()
                            ->where('email', $loginIdentifier)
                            ->first();
            }

            // Otherwise, assume it's a member_number
            return $this->createModel()->newQuery()
                        ->where('member_number', $loginIdentifier)
                        ->first();
        }

        // Fallback to default behavior if 'login_identifier' is not present
        return parent::retrieveByCredentials($credentials);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}