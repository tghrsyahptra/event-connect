<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\EventParticipant;
use App\Models\Event;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class PaymentService
{
    protected $secretKey;
    protected $publicKey;
    protected $webhookToken;
    protected $callbackUrl;
    protected $redirectUrl;

    public function __construct()
    {
        $this->secretKey = config('services.xendit.secret_key');
        $this->publicKey = config('services.xendit.public_key');
        $this->webhookToken = config('services.xendit.webhook_token');
        $this->callbackUrl = config('services.xendit.callback_url');
        $this->redirectUrl = config('services.xendit.redirect_url');
        
        // Configure Xendit SDK
        Configuration::setXenditKey($this->secretKey);
    }

    /**
     * Create invoice for event payment
     */
    public function createInvoice(EventParticipant $participant, array $options = [])
    {
        try {
            $event = $participant->event;
            $user = $participant->user;

            // Create Xendit invoice using SDK
            $invoiceApi = new InvoiceApi();
            
            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id' => 'event_' . $event->id . '_participant_' . $participant->id . '_' . time(),
                'amount' => (float) $event->price,
                'description' => 'Payment for event: ' . $event->title,
                'invoice_duration' => 86400, // 24 hours
                'customer' => [
                    'given_names' => $user->full_name ?? $user->name,
                    'email' => $user->email,
                ],
                'customer_notification_preference' => [
                    'invoice_created' => ['email'],
                    'invoice_reminder' => ['email'],
                    'invoice_paid' => ['email'],
                ],
                'success_redirect_url' => $this->redirectUrl . '?participant_id=' . $participant->id . '&status=success',
                'failure_redirect_url' => $this->redirectUrl . '?participant_id=' . $participant->id . '&status=failed',
                'currency' => 'IDR',
                'items' => [
                    [
                        'name' => $event->title,
                        'quantity' => 1,
                        'price' => (float) $event->price,
                        'category' => 'Event Registration',
                    ]
                ],
            ]);

            $invoice = $invoiceApi->createInvoice($createInvoiceRequest);

            // Update participant with payment reference
            $participant->update([
                'payment_reference' => $invoice['id'],
                'payment_url' => $invoice['invoice_url'],
                'payment_status' => 'pending',
            ]);

            Log::info('Xendit Invoice Created', [
                'participant_id' => $participant->id,
                'invoice_id' => $invoice['id'],
                'amount' => $event->price,
                'invoice_url' => $invoice['invoice_url'],
            ]);

            return [
                'success' => true,
                'invoice' => [
                    'id' => $invoice['id'],
                    'invoice_url' => $invoice['invoice_url'],
                    'amount' => $event->price,
                ],
                'payment_url' => $invoice['invoice_url'],
                'invoice_id' => $invoice['id'],
            ];

        } catch (\Exception $e) {
            Log::error('Xendit Invoice Creation Failed', [
                'participant_id' => $participant->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create virtual account for event payment
     */
    public function createVirtualAccount(EventParticipant $participant, string $bankCode = 'BCA')
    {
        try {
            $event = $participant->event;
            $user = $participant->user;

            // For now, simulate VA creation
            $vaId = 'va_' . time() . '_' . $participant->id;
            $accountNumber = '1234567890' . rand(1000, 9999);

            // Update participant with VA details
            $participant->update([
                'payment_reference' => $vaId,
                'payment_url' => $accountNumber,
                'payment_status' => 'pending',
                'payment_method' => 'virtual_account',
            ]);

            Log::info('Xendit Virtual Account Created (Simulated)', [
                'participant_id' => $participant->id,
                'va_id' => $vaId,
                'account_number' => $accountNumber,
            ]);

            return [
                'success' => true,
                'virtual_account' => [
                    'id' => $vaId,
                    'account_number' => $accountNumber,
                    'bank_code' => $bankCode,
                ],
                'account_number' => $accountNumber,
                'va_id' => $vaId,
            ];

        } catch (\Exception $e) {
            Log::error('Xendit Virtual Account Creation Failed', [
                'participant_id' => $participant->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create e-wallet payment
     */
    public function createEWalletPayment(EventParticipant $participant, string $ewalletType = 'OVO')
    {
        try {
            $event = $participant->event;
            $user = $participant->user;

            // For now, simulate e-wallet creation
            $ewalletId = 'ewallet_' . time() . '_' . $participant->id;
            $checkoutUrl = 'https://checkout.xendit.co/ewallet/' . $ewalletId;

            // Update participant with e-wallet details
            $participant->update([
                'payment_reference' => $ewalletId,
                'payment_url' => $checkoutUrl,
                'payment_status' => 'pending',
                'payment_method' => 'ewallet',
            ]);

            Log::info('Xendit E-Wallet Payment Created (Simulated)', [
                'participant_id' => $participant->id,
                'ewallet_id' => $ewalletId,
                'ewallet_type' => $ewalletType,
            ]);

            return [
                'success' => true,
                'ewallet' => [
                    'id' => $ewalletId,
                    'checkout_url' => $checkoutUrl,
                    'ewallet_type' => $ewalletType,
                ],
                'checkout_url' => $checkoutUrl,
                'ewallet_id' => $ewalletId,
            ];

        } catch (\Exception $e) {
            Log::error('Xendit E-Wallet Payment Creation Failed', [
                'participant_id' => $participant->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        $expectedSignature = hash_hmac('sha256', $payload, $this->webhookToken);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Handle payment webhook
     */
    public function handleWebhook(array $webhookData)
    {
        try {
            $externalId = $webhookData['external_id'];
            $status = $webhookData['status'];
            $paymentMethod = $webhookData['payment_method'] ?? 'unknown';

            // Extract participant ID from external_id
            if (strpos($externalId, 'event_payment_') === 0) {
                $participantId = explode('_', $externalId)[2];
            } elseif (strpos($externalId, 'event_va_') === 0) {
                $participantId = explode('_', $externalId)[2];
            } elseif (strpos($externalId, 'event_ewallet_') === 0) {
                $participantId = explode('_', $externalId)[2];
            } else {
                Log::warning('Unknown external_id format in webhook', ['external_id' => $externalId]);
                return false;
            }

            $participant = EventParticipant::find($participantId);
            if (!$participant) {
                Log::warning('Participant not found for webhook', ['participant_id' => $participantId]);
                return false;
            }

            // Update participant status based on payment status
            switch ($status) {
                case 'PAID':
                    $participant->update([
                        'payment_status' => 'paid',
                        'is_paid' => true,
                        'amount_paid' => $webhookData['amount'] ?? $participant->event->price,
                        'paid_at' => now(),
                    ]);
                    break;

                case 'EXPIRED':
                    $participant->update([
                        'payment_status' => 'expired',
                    ]);
                    break;

                case 'FAILED':
                    $participant->update([
                        'payment_status' => 'failed',
                    ]);
                    break;

                default:
                    Log::info('Unknown payment status in webhook', [
                        'status' => $status,
                        'participant_id' => $participantId,
                    ]);
            }

            Log::info('Payment webhook processed', [
                'participant_id' => $participantId,
                'status' => $status,
                'payment_method' => $paymentMethod,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'webhook_data' => $webhookData,
            ]);

            return false;
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $paymentReference)
    {
        try {
            // For now, simulate status check
            return [
                'success' => true,
                'status' => 'PENDING',
                'amount' => 100000,
                'paid_amount' => 0,
                'invoice_url' => 'https://checkout.xendit.co/web/' . $paymentReference,
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get payment status', [
                'payment_reference' => $paymentReference,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get available payment methods
     */
    public function getAvailablePaymentMethods()
    {
        return [
            'invoice' => [
                'name' => 'Credit Card',
                'description' => 'Pay with Visa, Mastercard, or JCB',
                'icon' => 'fas fa-credit-card',
            ],
            'virtual_account' => [
                'name' => 'Virtual Account',
                'description' => 'Pay via BCA, BNI, BRI, or Mandiri',
                'icon' => 'fas fa-university',
                'banks' => ['BCA', 'BNI', 'BRI', 'MANDIRI'],
            ],
            'ewallet' => [
                'name' => 'E-Wallet',
                'description' => 'Pay with OVO, DANA, LinkAja, or ShopeePay',
                'icon' => 'fas fa-mobile-alt',
                'providers' => ['OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY'],
            ],
        ];
    }
}