<?php

namespace App\Services;

use App\Mail\LoginOtpMail;
use App\Models\LoginOtp;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EmailOtpService
{
    /**
     * Generate and send a new OTP.
     */
    public function send(User $user): LoginOtp
    {
        $code = (string) random_int(100000, 999999);

        $otp = DB::transaction(function () use ($user, $code) {
            // Invalidate previous OTPs.
            LoginOtp::where('user_id', $user->id)
                ->whereNull('consumed_at')
                ->update([
                    'consumed_at' => now(),
                ]);

            return LoginOtp::create([
                'user_id' => $user->id,
                'code_hash' => Hash::make($code),
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
            ]);
        });

        Mail::to($user->email)->send(
            new LoginOtpMail($user, $code)
        );

        return $otp;
    }

    /**
     * Verify an OTP.
     */
    public function verify(LoginOtp $otp, string $code): bool
    {
        return DB::transaction(function () use ($otp, $code) {
            $lockedOtp = LoginOtp::whereKey($otp->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$lockedOtp->isUsable()) {
                return false;
            }

            if (!Hash::check($code, $lockedOtp->code_hash)) {
                $lockedOtp->increment('attempts');

                if ($lockedOtp->attempts >= 5) {
                    $lockedOtp->update([
                        'consumed_at' => now(),
                    ]);
                }

                return false;
            }

            $lockedOtp->update([
                'consumed_at' => now(),
            ]);

            return true;
        });
    }
}
