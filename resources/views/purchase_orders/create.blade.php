@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Add Order</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="po" class="form-label">PO Number</label>
                    <input type="text" class="form-control" id="po" name="po" value="{{ old('po') }}" required autofocus>
                    @error('po')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="party_id" class="form-label">Party</label>
                    <select class="form-select" id="party_id" name="party_id" required>
                        <option value="">Select Party</option>
                        @foreach ($parties as $p)
                            <option value="{{ $p->id }}" data-po-number="{{ $p->party_name }}">{{ $p->party_name }}</option>
                        @endforeach
                    </select>
                    @error('party_id')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="order_date" class="form-label">Order Date</label>
                    <input type="date" class="form-control" id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required autofocus>
                    @error('order_date')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="order_items" class="form-label">Order Items</label>
                    <div id="order_items_container">
                        <div class="repeatable mb-3 p-3 border rounded">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label for="design" class="form-label">Design</label>
                                    <select class="form-select" name="order_items[0][design]" data-name="design" id="design_0">
                                        <option value="">Select Design</option>
                                        @foreach ($designs as $design)
                                            <option value="{{ $design->id }}">{{ $design->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('order_items.0.name')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="size" class="form-label">Size</label>
                                    <select class="form-select" name="order_items[0][size]" data-name="size" id="size_0">
                                        <option value="">Select Size</option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('order_items.0.size_name')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="finish" class="form-label">Finish</label>
                                    <select class="form-select" name="order_items[0][finish]" data-name="finish" id="finish_0">
                                        <option value="">Select Finish</option>
                                        @foreach ($finishes as $finish)
                                            <option value="{{ $finish->id }}">{{ $finish->finish_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('order_items.0.finish_name')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="order_qty" class="form-label">Order Qty</label>
                                    <input type="number" class="form-control" name="order_items[0][order_qty]" data-name="order_qty" value="0" id="order_qty_0">
                                    @error('order_items.0.order_qty')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="pending_qty" class="form-label">Pending Qty</label>
                                    <input type="number" class="form-control" name="order_items[0][pending_qty]" data-name="pending_qty" value="0" readonly id="pending_qty_0">
                                    @error('order_items.0.pending_qty')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="planning_qty" class="form-label">Planning Qty</label>
                                    <input type="number" class="form-control" name="order_items[0][planning_qty]" data-name="planning_qty" value="0" readonly id="planning_qty_0">
                                    @error('order_items.0.planning_qty')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="production_qty" class="form-label">Production Qty</label>
                                    <input type="number" class="form-control" name="order_items[0][production_qty]" data-name="production_qty" value="0" readonly id="production_qty_0">
                                    @error('order_items.0.production_qty')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="short_qty" class="form-label">Short Qty</label>
                                    <input type="number" class="form-control" name="order_items[0][short_qty]" data-name="short_qty" value="0" readonly id="short_qty_0">
                                    @error('order_items.0.short_qty')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="remark" class="form-label">Remark</label>
                                    <input type="text" class="form-control" name="order_items[0][remark]" data-name="remark" id="remark_0">
                                    @error('order_items.0.remark')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mt-5">
                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-item" data-repeat-remove="repeatable">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-3" data-repeat-add="repeatable">Add Item</button>
                </div>

                <button type="submit" class="btn btn-success mt-4">Save Order</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            repeat();
        });
    </script>
    <script src="{{ asset('js/repeat.js') }}"></script>
@endsection
