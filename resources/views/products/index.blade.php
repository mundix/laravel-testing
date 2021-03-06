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
                    @if(auth()->user()->is_admin)
                            <a href="{{route('products.create')}}" class="btn btn-primary mb-3">Add new product</a>

                    @endif
                    <table class="table">
                        <tr>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Price (EUR)</th>
                        </tr>
                        @forelse($products as $product)
                            <tr>
                                <td>{{$product->name}}</td>
                                <td>{{$product->price}}</td>
                                <td>{{$product->price_eur}}</td>
                                <td><a href="{{route('products.edit', $product)}}" class="btn btn-primary">Edit</a></td>
                                <td>
                                    <form action="{{route('products.destroy', $product)}}"
                                          method="POST" onsubmit="return confirm('Are you sure?')"
                                          style="display:inline-block"
                                    >
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    No Products Found
                                </td>
                            </tr>
                        @endforelse
                    </table>
                    {{$products->links()}}
                </div>

            </div>
        </div>
    </div>
@endsection
