@extends('SGEntryPass::layouts.auth')
@section('content')
    <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-red-600">Invalid Link</h2>
            </div>
            <div class="text-sm text-center">
                <a href="/login-link" class="font-medium text-indigo-600 hover:text-indigo-500">CLick to login</a>
            </div>
        </div>
    </div>
@stop
