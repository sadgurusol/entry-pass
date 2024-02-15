@extends('SGEntryPass::layouts.auth')
@section('content')
    <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">{{config('app.name')}} - Reset Password</h2>
            </div>
            <form class="mt-8 space-y-6" action="/create-password" method="POST">
                @csrf()
                <input type="hidden" name="remember" value="true">
                <input type="hidden" name="token" value="{{$token}}">
                <div>
                    <label for="password" class="sr-only">New Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="relative block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="Enter New Password">
                    @if($errors->has('password'))
                        <span style="color: red">{{$errors->first('password')}}</span>
                    @endif
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <a href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in with password</a>
                    </div>
                    <div class="text-sm">
                        <a href="/login-link" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in with link</a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                      <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <!-- Heroicon name: mini/lock-closed -->
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                        </svg>
                      </span>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
