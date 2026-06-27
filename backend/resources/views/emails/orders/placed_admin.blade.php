<x-mail::message>
# 📦 New Order Received!

You have received a new order (**#{{ $order->order_number }}**) from **{{ $order->customer_name }}**.

<x-mail::panel>
### 📄 Order Details
- **Total Amount:** Rs. {{ number_format($order->total, 2) }}
- **Payment Method:** {{ strtoupper($order->payment_method) }}
- **Customer Phone:** {{ $order->customer_phone }}
- **Customer Email:** {{ $order->customer_email }}
</x-mail::panel>

### 🛍️ Items Ordered

<x-mail::table>
| Item       | Qty         | Price  |
| :--------- | :---------- | :----- |
@foreach($order->orderItems as $item)
| {{ $item->product_name ?? 'Product' }} | {{ $item->quantity }} | Rs. {{ number_format($item->unit_price, 2) }} |
@endforeach
</x-mail::table>

<x-mail::button :url="config('app.url') . '/admin/orders'">
View Orders in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} System
</x-mail::message>
