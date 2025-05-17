@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Size List</h4>
        <a href="{{ route('sizes.create') }}" class="btn btn-primary btn-sm">+ Add Size</a>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Size Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sizes as $size)
                    <tr>
                        <td>{{ $size->id }}</td>
                        <td>{{ $size->size_name }}</td>
                        <td>
                            <a href="{{ route('sizes.edit', $size->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('sizes.destroy', $size->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
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
            {{ $sizes->links() }}
        </div>
    </div>
</div>
@endsection
