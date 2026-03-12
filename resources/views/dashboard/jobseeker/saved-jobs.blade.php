@extends('layouts.app')

@section('title','Saved Jobs')

@section('content')

<div class="max-w-6xl mx-auto">

<h1 class="text-2xl font-bold mb-6">
Saved Jobs
</h1>

@if($savedJobs->isEmpty())

<p class="text-gray-500">
You have no saved jobs yet.
</p>

@else

<div class="grid md:grid-cols-2 gap-6">

@foreach($savedJobs as $job)

<x-card-job :job="$job"/>

@endforeach

</div>

@endif

</div>

@endsection