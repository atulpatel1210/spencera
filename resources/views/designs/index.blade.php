@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Designs</h4>
        <a href="{{ route('designs.create') }}" class="btn btn-primary btn-sm">+ Add Design</a>
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
                @foreach($designs as $design)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $design->name }}</td>
                        <td>
                            <a href="{{ route('designs.edit', $design) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('designs.destroy', $design) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Del</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $designs->links() }}
    </div>
</div>
@endsection
