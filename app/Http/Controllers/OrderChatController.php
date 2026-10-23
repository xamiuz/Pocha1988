<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderChatController extends Controller
{
    /**
     * Ambil daftar pesan chat untuk pesanan tertentu.
     */
    public function index(Request $request, string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $isAdmin = auth()->check()
            || $request->query('as') === 'admin'
            || str_contains((string) $request->header('referer'), '/admin');
        $isPeek = $request->boolean('peek');

        // Hitung unread count sebelum update
        $unreadForCustomer = $order->messages()->where('sender_type', 'admin')->where('is_read', false)->count();
        $unreadForAdmin = $order->messages()->where('sender_type', 'customer')->where('is_read', false)->count();

        // Tandai pesan sudah dibaca jika bukan peek
        if (! $isPeek) {
            if ($isAdmin) {
                $order->messages()->where('sender_type', 'customer')->where('is_read', false)->update(['is_read' => true]);
                $unreadForAdmin = 0;
            } else {
                $order->messages()->where('sender_type', 'admin')->where('is_read', false)->update(['is_read' => true]);
                $unreadForCustomer = 0;
            }
        }

        $messages = $order->messages()->oldest()->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'sender_name' => $msg->sender_name,
                'message' => $msg->message,
                'is_read' => $msg->is_read,
                'time' => $msg->created_at->setTimezone('Asia/Makassar')->format('H:i'),
                'date' => $msg->created_at->setTimezone('Asia/Makassar')->format('d M'),
            ];
        });

        return response()->json([
            'success' => true,
            'order' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'status' => $order->status,
                'driver_name' => $order->driver_name ?? 'Kurir 1988 Pocha',
            ],
            'unread_for_customer' => $unreadForCustomer,
            'unread_for_admin' => $unreadForAdmin,
            'messages' => $messages,
        ]);
    }

    /**
     * Kirim pesan baru ke pesanan.
     */
    public function store(Request $request, string $orderNumber): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $isAdmin = auth()->check()
            || $request->input('sender_type') === 'admin'
            || str_contains((string) $request->header('referer'), '/admin');
        $senderType = $isAdmin ? 'admin' : 'customer';
        $senderName = $isAdmin ? ($request->input('sender_name') ?: 'Admin Pocha') : $order->customer_name;

        $msg = $order->messages()->create([
            'sender_type' => $senderType,
            'sender_name' => $senderName,
            'message' => trim($request->input('message')),
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'sender_name' => $msg->sender_name,
                'message' => $msg->message,
                'is_read' => $msg->is_read,
                'time' => $msg->created_at->setTimezone('Asia/Makassar')->format('H:i'),
                'date' => $msg->created_at->setTimezone('Asia/Makassar')->format('d M'),
            ],
        ]);
    }
}
