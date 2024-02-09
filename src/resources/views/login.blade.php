@extends('SGEntryPass::layouts.auth')
@section('content')
    <div class="row">
        <div class="col-4 offset-4 mt-5">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">{{config('app.name')}} - Sign in to your account</h2>
                    </div>
                    <form class="mt-8 space-y-6" action="/login" method="POST">
                        @csrf()
                        <input type="hidden" name="remember" value="true">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email/Phone</label>
                            <input name="phone" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">

                            @if($errors->has('phone'))
                                <div class="invalid-feedback d-block">{{$errors->first('phone')}}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" id="exampleInputPassword1">
                            @if($errors->has('password'))
                                <div class="invalid-feedback d-block">{{$errors->first('password')}}</div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center justify-content-between my-4">
                            <div class="text-sm">
                                <a href="/login-link" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in with link</a>
                            </div>
                            <div class="text-sm">
                                <a href="/forgot-password" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <div class="text-sm text-center mt-10">
                            <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">Create new account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
