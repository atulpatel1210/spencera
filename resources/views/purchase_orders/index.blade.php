@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Orders List</h4>
        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">+ New Order</a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>PO</th>
                    <th>Party Name</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->po }}</td>
                        <td>
                            {{ $order->party?->party_name ?? 'N/A' }}
                        </td>
                        <td>{{ $order->order_date }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary btn-sm ms-2">Edit</a>
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection