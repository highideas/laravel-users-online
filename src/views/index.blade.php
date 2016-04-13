@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Users Online</div>

                <div class="panel-body">
                    @foreach ($users as $user)
                        @if($user->isOnline())
                            <p>This is user {{ $user->name }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

