<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ShippingCost;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    /**
     * HALAMAN PAYMENT
     */
   public function show($order_id)
    {
        $order = Order::where('order_code', $order_id)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
        }

        if (!$order->midtrans_response) {
            return redirect()->route('home')->with('error', 'Data pembayaran tidak tersedia.');
        }

        $status = json_decode($order->midtrans_response);

        if (!$status) {
            return redirect()->route('home')->with('error', 'Data pembayaran tidak valid.');
        }

        // =========================
        // NORMALISASI
        // =========================
        $paymentType = strtolower($status->payment_type ?? '');
        $bank = null;
        $vaNumber = null;

        // =========================
        // VA (BRI, BCA, dll)
        // =========================
        if (isset($status->va_numbers[0])) {
            $bank = $status->va_numbers[0]->bank;
            $vaNumber = $status->va_numbers[0]->va_number;
        }

        // =========================
        // MANDIRI (ECHANNEL / FALLBACK)
        // =========================
        elseif (isset($status->bill_key)) {
            $bank = 'mandiri';
            $vaNumber = $status->bill_key;

            $paymentType = 'bank_transfer';
        }

        // =========================
        // E-WALLET & QRIS
        // =========================
        $qrString = $status->qr_string ?? null;
        $actions  = $status->actions ?? [];

        $deeplinkUrl = null;
        $qrUrl = null;

        if (!empty($actions)) {
            foreach ($actions as $action) {

                if ($action->name == 'deeplink-redirect') {
                    $deeplinkUrl = $action->url;
                }

                if ($action->name == 'generate-qr-code') {
                    $qrUrl = $action->url;
                }

                // ShopeePay fallback
                if ($action->name == 'url') {
                    $deeplinkUrl = $action->url;
                }
            }
        }

        return view('pages.payment', [
            'response'     => $status,
            'payment_type' => $paymentType,
            'bank'         => $bank,
            'va_number'    => $vaNumber,
            'qr_string'    => $qrString,
            'actions'      => $actions,
            'deeplinkUrl'  => $deeplinkUrl,
            'qrUrl'        => $qrUrl,
            'subtotal' => $order->total_price - $order->shipping_cost,
            'total'        => $order->total_price
        ]);
    }
    /**
     * HANDLE CALLBACK MIDTRANS (WEBHOOK)
     */
    public function handleNotification(Request $request)
    {
        try {
            Log::info('MIDTRANS CALLBACK MASUK', $request->all());

            $orderCode = $request->order_id;
            $transactionStatus = $request->transaction_status;

            $order = Order::where('order_code', $orderCode)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // =========================
            // UPDATE STATUS ORDER
            // =========================
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $order->update(['status' => 'complete']);

            } elseif ($transactionStatus == 'pending') {
                $order->update(['status' => 'process']);

            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->update(['status' => 'cancelled']);
            }

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * HALAMAN SUCCESS
     */
    public function success($order_id)
    {
        $order = Order::where('order_code', $order_id)->firstOrFail();

        return view('pages.order-success', [
            'order' => $order
        ]);
    }

    //===========
    // ONKIR 
    //=========
  public function getOngkir(Request $request)
{
    $cityName = strtoupper($request->city_name); // Paksa jadi kapital dulu

    $shipping = ShippingCost::where('city', $cityName)->first();

    $cost = $shipping ? $shipping->cost : 0;

    return response()->json([
        'success' => true,
        'cost' => (int) $cost 
    ]);
}
}