<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected SmsService $sms;

    public function __construct(SmsService $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Handle custom checkout orders placement
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'nullable|string|max:10',
            'payment_method' => 'required|in:payhere,cod,bank_deposit',
            'total_amount' => 'required|numeric',
            'items' => 'required|array|min:1',
            'bank_slip' => 'nullable|string'
        ]);

        $orderNumber = 'MF-' . date('Ymd') . '-' . mt_rand(1000, 9999);
        $status = 'pending';
        $paymentStatus = 'pending';
        $slipPath = null;

        if ($request->payment_method === 'bank_deposit' && !empty($request->bank_slip)) {
            // The bank_slip is expected to be a base64 encoded data URL
            $base64_str = substr($request->bank_slip, strpos($request->bank_slip, ",")+1);
            $image = base64_decode($base64_str);
            
            // Get the extension from the mime type, or default to png
            $extension = 'png';
            if (preg_match('/data:(image|application)\/(.*?);/', $request->bank_slip, $match)) {
                $extension = $match[2];
            }
            
            $fileName = "slip_{$orderNumber}_" . time() . '.' . $extension;
            Storage::disk('public')->put('slips/' . $fileName, $image);
            $slipPath = 'public/slips/' . $fileName;
        }

        if ($request->payment_method === 'cod') {
            $status = 'confirmed';
        }

        try {
            $order = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $orderNumber, $status, $paymentStatus, $slipPath) {
                // Lock rows for stock verification
                foreach ($request->items as $item) {
                    $productId = $item['id'] ?? null;
                    $size = $item['size'] ?? 'Standard';
                    $quantity = $item['quantity'] ?? 1;
                    if ($productId) {
                        $variant = \App\Models\ProductVariant::where('product_id', $productId)
                            ->where('size', $size)
                            ->lockForUpdate()
                            ->first();

                        if ($variant && $variant->stock < $quantity) {
                            throw new \Exception("Out of stock: Only {$variant->stock} remaining for size {$size}.");
                        } else if (!$variant) {
                            $product = \App\Models\Product::where('id', $productId)->lockForUpdate()->first();
                            if ($product && $product->stock < $quantity) {
                                throw new \Exception("Out of stock: Only {$product->stock} remaining.");
                            }
                        }
                    }
                }

                // Save Order
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->phone,
                    'customer_email' => $request->email,
                    'customer_address' => $request->address,
                    'customer_city' => $request->district,
                    'customer_postal_code' => $request->postal_code,
                    'subtotal' => $request->total_amount, // Simplified
                    'total' => $request->total_amount,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $paymentStatus,
                    'status' => $status,
                    'bank_slip_path' => $slipPath,
                ]);

                // Save Order Items & Deduct Stock
                foreach ($request->items as $item) {
                    $productId = $item['id'] ?? null;
                    $quantity = $item['quantity'] ?? 1;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'product_name' => $item['name'],
                        'size' => $item['size'] ?? 'Standard',
                        'unit_price' => $item['price'],
                        'quantity' => $quantity,
                        'subtotal' => $item['price'] * $quantity,
                        'product_image' => $item['image'] ?? null,
                    ]);

                    // Auto-deduct stock
                    if ($productId) {
                        $size = $item['size'] ?? 'Standard';
                        $variant = \App\Models\ProductVariant::where('product_id', $productId)->where('size', $size)->first();
                        if ($variant) {
                            $variant->stock = max(0, $variant->stock - $quantity);
                            $variant->save();
                        }
                        
                        $product = \App\Models\Product::find($productId);
                        if ($product) {
                            $product->stock = max(0, $product->stock - $quantity);
                            $product->save();
                        }
                    }
                }

                return $order;
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }

        Log::info("Order successfully registered: #{$orderNumber}");

        if ($status === 'confirmed' || $status === 'pending') {
            $smsData = [
                'name' => $order->customer_name,
                'order_id' => $order->order_number,
                'total' => number_format($order->total, 2)
            ];
            
            // Send SMS to customer (Fallback if template is empty)
            if (!$this->sms->sendOrderPlacedCustomer($order->customer_phone, $smsData)) {
                $this->sms->sendSms($order->customer_phone, "Dear {$order->customer_name}, your order {$order->order_number} has been received. Total: LKR {$smsData['total']}");
            }

            // Send SMS to admins
            $this->sms->sendOrderPlacedAdmin($smsData);
        }

        // Send email to customer
        try {
            $itemDetails = "";
            foreach ($request->items as $item) {
                $itemSize = $item['size'] ?? 'Standard';
                $itemDetails .= "- {$item['name']} (Size: {$itemSize}) x {$item['quantity']} = LKR " . number_format($item['price'] * $item['quantity'], 2) . "\n";
            }

            $emailBody = "Dear {$request->customer_name},\n\n"
                       . "Thank you for your order at Maneesha Fashion!\n\n"
                       . "Your Order Number: {$orderNumber}\n"
                       . "Order Status: " . ucfirst($status) . "\n\n"
                       . "Order Details:\n"
                       . $itemDetails . "\n"
                       . "Total Amount: LKR " . number_format($request->total_amount, 2) . "\n\n"
                       . "We will update you on the progress once your order is processed.\n\n"
                       . "Best Regards,\nManeesha Fashion Team";

            \Mail::raw($emailBody, function($msg) use ($request, $orderNumber) {
                $msg->to($request->email)->subject("Order Confirmation #{$orderNumber} - Maneesha Fashion");
            });
        } catch (\Exception $e) {
            Log::warning("Failed to send order email: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Order processed successfully.',
            'order' => $order->load('orderItems')
        ], 201);
    }

    /**
     * List orders for admin dashboard
     */
    public function listOrders(Request $request)
    {
        if ($request->user() && $request->user()->role === 'inventory') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = Order::with('orderItems');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        return response()->json($orders);
    }

    /**
     * Get statistics for admin dashboard
     */
    public function dashboardStats()
    {
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        // Get recent orders
        $recentOrders = Order::with('orderItems')->orderBy('created_at', 'desc')->take(5)->get();

        // Get district stats
        $districtStats = Order::select('customer_city', \DB::raw('count(*) as count'))
            ->groupBy('customer_city')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // Simulate active visitors
        $activeVisitors = rand(15, 65);

        return response()->json([
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'deliveredOrders' => $deliveredOrders,
            'recentOrders' => $recentOrders,
            'districtStats' => $districtStats,
            'activeVisitors' => $activeVisitors
        ]);
    }

    /**
     * Get statistics specifically for the orders page cards
     */
    public function orderStats()
    {
        return response()->json([
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'dispatchedOrders' => Order::whereIn('status', ['dispatched', 'delivered'])->count(),
            'bankDepositOrders' => Order::where('payment_method', 'bank_deposit')->count(),
            'payhereOrders' => Order::where('payment_method', 'payhere')->count(),
            'codOrders' => Order::where('payment_method', 'cod')->count()
        ]);
    }

    /**
     * Dispatching and marking orders status
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,dispatched,delivered,cancelled'
        ]);

        $order = Order::with('orderItems')->findOrFail($id);
        
        // Restore stock if cancelling an active order
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                if ($item->product_id) {
                    $variant = \App\Models\ProductVariant::where('product_id', $item->product_id)->where('size', $item->size)->first();
                    if ($variant) {
                        $variant->stock += $item->quantity;
                        $variant->save();
                    }
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->stock += $item->quantity;
                        $product->save();
                    }
                }
            }
        }

        $order->status = $request->status;
        
        if ($request->status === 'dispatched') {
            $order->dispatched_at = now();
        } else if ($request->status === 'delivered') {
            $order->delivered_at = now();
        }
        
        $order->save();

        // Send SMS to customer on status update
        try {
            $smsData = [
                'name' => $order->customer_name,
                'order_id' => $order->order_number,
                'status' => strtoupper($request->status)
            ];

            // Attempt to send template SMS
            if (!$this->sms->sendOrderStatusCustomer($order->customer_phone, $smsData)) {
                // Fallback
                $statusMessages = [
                    'confirmed' => "Your Maneesha Fashion order #{$order->order_number} has been confirmed.",
                    'processing' => "Your Maneesha Fashion order #{$order->order_number} is now being processed.",
                    'dispatched' => "Great news! Your Maneesha Fashion order #{$order->order_number} has been dispatched.",
                    'delivered' => "Your Maneesha Fashion order #{$order->order_number} has been delivered. Thank you!",
                    'cancelled' => "Your Maneesha Fashion order #{$order->order_number} has been cancelled."
                ];
    
                if (isset($statusMessages[$request->status])) {
                    $this->sms->sendSms($order->customer_phone, $statusMessages[$request->status]);
                }
            }
        } catch (\Exception $e) {
            Log::warning("Failed to send status update SMS: " . $e->getMessage());
        }

        // Send email to customer on cancellation
        if ($request->status === 'cancelled') {
            try {
                $emailBody = "Dear {$order->customer_name},\n\n"
                           . "We regret to inform you that your order #{$order->order_number} has been cancelled.\n\n"
                           . "If you have any questions or concerns, please contact our support team.\n\n"
                           . "Best Regards,\nManeesha Fashion Team";

                \Mail::raw($emailBody, function($msg) use ($order) {
                    $msg->to($order->customer_email)->subject("Order Cancelled #{$order->order_number} - Maneesha Fashion");
                });
            } catch (\Exception $e) {
                Log::warning("Failed to send order cancellation email: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Order {$order->order_number} transitioned successfully to {$request->status}.",
            'order' => $order
        ]);
    }

    /**
     * View Bank Slip
     */
    public function viewSlip($id)
    {
        $order = Order::findOrFail($id);
        if (!$order->bank_slip_path) {
            return response()->json(['error' => 'No slip found'], 404);
        }
        
        $path = str_replace('public/', '', $order->bank_slip_path);
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File not found on disk'], 404);
        }
        
        return response()->file($fullPath);
    }

    /**
     * Download Bank Slip
     */
    public function downloadSlip($id)
    {
        $order = Order::findOrFail($id);
        if (!$order->bank_slip_path) {
            return response()->json(['error' => 'No slip found'], 404);
        }
        
        $path = str_replace('public/', '', $order->bank_slip_path);
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File not found on disk'], 404);
        }
        
        return response()->download($fullPath);
    }

    /**
     * Track orders by phone number
     */
    public function trackOrders(Request $request)
    {
        $orderId = $request->query('order_id');
        
        if (!$orderId) {
            return response()->json(['error' => 'Order ID is required to track orders'], 400);
        }

        $orders = Order::with('orderItems')
            ->where('order_number', $orderId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }
}
