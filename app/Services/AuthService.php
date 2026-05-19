<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    // -------------------------------------------------------------------------
    // Registration
    // -------------------------------------------------------------------------

    public function registerUser(array $data): User
    {
        return User::create([
            'role'     => 'user',
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'otp'      => rand(100000, 999999),
            'code'     => md5(uniqid()),
        ]);
    }

    /**
     * Register a professional (buyer / seller / capital_raiser / broker).
     *
     * @param  array  $data  Must include a 'role' key with one of the 4 professional roles.
     */
    public function registerProfessional(array $data): User
    {
        return User::create([
            'role'       => $data['role'],
            'name'       => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'password'   => Hash::make($data['password']),
            'otp'        => rand(100000, 999999),
            'code'       => md5(uniqid()),
        ]);
    }

    // -------------------------------------------------------------------------
    // Login
    // -------------------------------------------------------------------------

    public function loginUser(string $email, string $password): array
    {
        $user = User::where('email', $email)->where('role', 'user')->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        }

        if (!$user->verified) {
            throw ValidationException::withMessages(['email' => 'Please verify your account first.']);
        }

        $token = $user->createToken('auth_token', ['role:user'])->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    /**
     * Login a professional. Token ability encodes their specific role
     * (e.g. role:buyer, role:seller, role:capital_raiser, role:broker).
     */
    public function loginProfessional(string $email, string $password): array
    {
        $user = User::where('email', $email)
            ->whereIn('role', User::PROFESSIONAL_ROLES)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        }

        if (!$user->verified) {
            throw ValidationException::withMessages(['email' => 'Please verify your account first.']);
        }

        $token = $user->createToken('auth_token', ['role:' . $user->role])->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    // -------------------------------------------------------------------------
    // OTP / Password reset
    // -------------------------------------------------------------------------

    public function verifyOtp(User $user, int $otp): bool
    {
        if ($user->otp !== $otp) {
            return false;
        }

        $user->update(['verified' => 1, 'otp' => 0]);
        return true;
    }

    public function generatePasswordResetOtp(User $user): int
    {
        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp]);
        return $otp;
    }

    public function resetPassword(User $user, int $otp, string $password): bool
    {
        if ($user->otp !== $otp) {
            return false;
        }

        $user->update([
            'password' => Hash::make($password),
            'otp'      => 0,
        ]);

        return true;
    }
}
