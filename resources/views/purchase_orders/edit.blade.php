@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Edit Order</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="po" class="form-label">PO Number</label>
                    <input type="text" class="form-control" id="po" name="po" value="{{ old('po', $order->po) }}" required autofocus>
                    @error('po')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="order_items" class="form-label">Order Items</label>
                    <div id="order_items_container">
                        @foreach ($order->orderItems as $key => $orderItem)
                            <div class="repeatable mb-3 p-3 border rounded">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label for="design" class="form-label">Design</label>
                                        <select class="form-select" name="order_items[{{ $key }}][design]" data-name="design" id="design_{{ $key }}">
                                            <option value="">Select Design</option>
                                            @foreach ($designs as $design)
                                                <option value="{{ $design->id }}" {{ $orderItem->design == $design->id ? 'selected' : '' }}>{{ $design->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('order_items.{{ $key }}.design')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="size" class="form-label">Size</label>
                                        <select class="form-select" name="order_items[{{ $key }}][size]" data-name="size" id="size_{{ $key }}">
                                            <option value="">Select Size</option>
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}" {{ $orderItem->size == $size->id ? 'selected' : '' }}>{{ $size->size_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('order_items.{{ $key }}.size_name')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="finish" class="form-label">Finish</label>
                                        <select class="form-select" name="order_items[{{ $key }}][finish]" data-name="finish" id="finish_{{ $key }}">
                                            <option value="">Select Finish</option>
                                            @foreach ($finishes as $finish)
                                                <option value="{{ $finish->id }}" {{ $orderItem->finish == $finish->id ? 'selected' : '' }}>{{ $finish->finish_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('order_items.{{ $key }}.finish_name')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="order_qty" class="form-label">Order Qty</label>
                                        <input type="number" class="form-control" name="order_items[{{ $key }}][order_qty]" data-name="order_qty" value="{{ old('order_qty', $orderItem->order_qty) }}" id="order_qty_{{ $key }}">
                                        @error('order_items.{{ $key }}.order_qty')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="pending_qty" class="form-label">Pending Qty</label>
                                        <input type="number" class="form-control" name="order_items[{{ $key }}][pending_qty]" data-name="pending_qty" value="{{ old('pending_qty', $orderItem->pending_qty) }}" readonly id="pending_qty_{{ $key }}">
                                        @error('order_items.{{ $key }}.pending_qty')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="planning_qty" class="form-label">Planning Qty</label>
                                        <input type="number" class="form-control" name="order_items[{{ $key }}][planning_qty]" data-name="planning_qty" value="{{ old('planning_qty', $orderItem->planning_qty) }}" readonly id="planning_qty_{{ $key }}">
                                        @error('order_items.{{ $key }}.planning_qty')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="production_qty" class="form-label">Production Qty</label>
                                        <input type="number" class="form-control" name="order_items[{{ $key }}][production_qty]" data-name="production_qty" value="{{ old('production_qty', $orderItem->production_qty) }}" readonly id="production_qty_{{ $key }}">
                                        @error('order_items.{{ $key }}.production_qty')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="short_qty" class="form-label">Short Qty</label>
                                        <input type="number" class="form-control" name="order_items[{{ $key }}][short_qty]" data-name="short_qty" value="{{ old('short_qty', $orderItem->short_qty) }}" readonly id="short_qty_{{ $key }}">
                                        @error('order_items.{{ $key }}.short_qty')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="remark" class="form-label">Remark</label>
                                        <input type="text" class="form-control" name="order_items[{{ $key }}][remark]" data-name="remark" value="{{ old('remark', $orderItem->remark) }}" id="remark_{{ $key }}">
                                        @error('order_items.{{ $key }}.remark')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 mt-5">
                                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-item" data-repeat-remove="repeatable">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary mt-3" data-repeat-add="repeatable">Add Item</button>
                </div>

                <button type="submit" class="btn btn-success mt-4">Update Order</button>
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
