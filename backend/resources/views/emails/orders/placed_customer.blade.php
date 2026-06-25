<x-mail::message>
# Thank you for your order!

Hi {{ $order->customer_name }},

We've received your order **#{{ $order->order_number }}** and are currently processing it. Thank you for shopping with Maneesha Fashion!

### Order Summary

<x-mail::table>
| Item       | Qty         | Price  |
| :--------- | :---------- | :----- |
@foreach($order->orderItems as $item)
| {{ $item->product_name ?? 'Product' }} | {{ $item->quantity }} | Rs. {{ number_format($item->unit_price, 2) }} |
@endforeach
</x-mail::table>

**Total Amount:** Rs. {{ number_format($order->total, 2) }}

**Payment Method:** {{ strtoupper($order->payment_method) }}

**Shipping Address:**
{{ $order->customer_address }}<br>
{{ $order->customer_city }} {{ $order->customer_postal_code }}

You can track your order status on our website anytime.

<x-mail::button :url="config('app.frontend_url') . '/orders'">
Track My Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
