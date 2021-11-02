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

                        <form action="{{route('products.update', [$product->id])}}" method="post"  >
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label for="product_name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="product_name" aria-describedby="nameHelp" name="name" value="{{old('name', $product->name)}}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="product_price">Price</label>
                                <input type="text" class="form-control @error('price') is-invalid @enderror" id="product_price" aria-describedby="priceHelp" name="price" value="{{old('price', $product->price)}}">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                </div>

            </div>
        </div>
    </div>
@endsection
