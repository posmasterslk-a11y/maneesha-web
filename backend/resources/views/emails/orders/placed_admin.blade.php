<x-mail::message>
# New Order Received!

You have received a new order (**#{{ $order->order_number }}**) from **{{ $order->customer_name }}**.

### Order Details

- **Total Amount:** Rs. {{ number_format($order->total, 2) }}
- **Payment Method:** {{ strtoupper($order->payment_method) }}
- **Customer Phone:** {{ $order->customer_phone }}
- **Customer Email:** {{ $order->customer_email }}

### Items

<x-mail::table>
| Item       | Qty         | Price  |
| :--------- | :---------- | :----- |
@foreach($order->items as $item)
| {{ $item->product_name ?? 'Product' }} | {{ $item->quantity }} | Rs. {{ number_format($item->price, 2) }} |
@endforeach
</x-mail::table>

<x-mail::button :url="config('app.url') . '/admin/orders'">
View Orders in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} System
</x-mail::message>
