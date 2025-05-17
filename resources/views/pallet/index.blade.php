@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Pallet List</h4>
        <a href="{{ route('pallet.create') }}" class="btn btn-primary btn-sm">+ Add Pallet</a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pallet Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pallets as $pallet)
                    <tr>
                        <td>{{ $pallet->id }}</td>
                        <td>{{ $pallet->pallet_name }}</td>
                        <td>
                            <a href="{{ route('pallet.edit', $pallet->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('pallet.destroy', $pallet->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $pallets->links() }}
        </div>
    </div>
</div>
@endsection
