<x-mail::message>
# Order Status Update

Hi {{ $order->customer_name }},

The status of your order **#{{ $order->order_number }}** has been updated.

**New Status:** {{ strtoupper($order->status) }}

@if($order->status === 'cancelled')
**Cancellation Reason:** {{ $order->cancellation_reason ?? 'Not specified' }}
@endif

We will keep you informed of any further updates.

<x-mail::button :url="config('app.frontend_url') . '/orders'">
Track My Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
