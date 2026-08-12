<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PaymentVerificationService
{
    public function uploadReceipt(Payment $payment, $receiptFile): Payment
    {
        $path = $receiptFile->store('receipts', 'public');
        $payment->update([
            'receipt_path' => $path,
            'payment_status' => 'pending',
        ]);

        return $payment;
    }

    public function verifyPayment(Payment $payment, User $staff, string $status, ?string $rejectionReason = null): Payment
    {
        return DB::transaction(function () use ($payment, $staff, $status, $rejectionReason) {
            $payment->update([
                'payment_status' => $status,
                'verified_by' => $staff->id,
                'verified_at' => now(),
                'rejection_reason' => $rejectionReason,
            ]);

            if ($status === 'paid') {
                $payment->application->update(['status' => 'Under Review']);
            }

            AuditLogService::log('payment_verified', "Payment for control number {$payment->control_number} marked as {$status} by staff ID {$staff->id}");

            return $payment;
        });
    }
}
