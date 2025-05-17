@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>finishes</h4>
        <a href="{{ route('finishes.create') }}" class="btn btn-primary btn-sm">+ Add Finish</a>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($finishes as $finish)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $finish->finish_name }}</td>
                        <td>
                            <a href="{{ route('finishes.edit', $finish) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('finishes.destroy', $finish) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Del</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $finishes->links() }}
    </div>
</div>
@endsection
