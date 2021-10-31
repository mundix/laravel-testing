@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Products</div>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{session('status')}}
                        </div>
                    @endif
                    <div class="table">
                        <tr>
                            <td>Product Name</td>
                            <td>Price</td>
                        </tr>
                        @forelse($products as $product)
                            <tr>
                                <td>{{$product->name}}</td>
                                <td>{{$product->price}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    No Products Found
                                </td>
                            </tr>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
