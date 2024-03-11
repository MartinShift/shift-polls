@extends('layouts.myapp')

@section('page_title', 'Test page')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{$message}}</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus aliquam cum dolorum ducimus
                    eaque
                    ex
                    excepturi
                    hic iusto, maxime natus necessitatibus, nihil optio pariatur repellat sequi sunt velit
                    veritatis?</p>
            </div>
        </div>
        <button id="swal">SWAL test</button>    </div>
@endsection


@push('scripts')
    <script>
        document.getElementById('swal').addEventListener('click', () => {
            Swal.fire({
                title: "Good job!",
                text: "You clicked the button!",
                icon: "success"
            });
        })
    </script>
@endpush

@push('styles')
    <style>
        h1 {color: red;}
    </style>
@endpush
