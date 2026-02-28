@extends('layouts.app')

@section('title', 'My Applications - WorkNepal')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">My Job Applications</h2>

        @if($applications->isEmpty())
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-folder-open fa-3x mb-3"></i>
                <h5>You haven't applied to any jobs yet.</h5>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary mt-3">
                    Start Applying Now
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Company</th>
                            <th>Applied On</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr>
                                <td><a href="{{ route('jobs.show', $app->jobPosting) }}">{{ $app->jobPosting->title }}</a></td>
                                <td>{{ $app->jobPosting->company->company_name }}</td>
                                <td>{{ $app->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $app->status === 'hired' ? 'success' : 
                                        $app->status === 'rejected' ? 'danger' : 
                                        $app->status === 'shortlisted' ? 'warning' : 'secondary' 
                                    }}">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection