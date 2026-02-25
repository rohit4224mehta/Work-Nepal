@extends('layouts.guest')

@section('title', $company->name . ' - WorkNepal')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        <h2>{{ $company->name }}</h2>

        @include('components.badge-verified', ['status' => $company->verification_status])

        <p class="mt-3">{{ $company->description }}</p>

        <hr>

        <h5>Open Positions</h5>

        @foreach($company->jobs as $job)
            @include('components.card-job', ['job' => $job])
        @endforeach
    </div>
</div>

@endsection