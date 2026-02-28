@extends('layouts.app')

@section('title', 'Employer Dashboard - WorkNepal')

@section('content')

<!-- Welcome & Quick Stats Banner -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    @if(Auth::user()->company->logo_path)
                        <img src="{{ Storage::url(Auth::user()->company->logo_path) }}" 
                             alt="{{ Auth::user()->company->company_name }}" 
                             class="rounded-circle me-3 shadow" width="80" height="80" style="object-fit: cover; border: 3px solid white;">
                    @else
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow" style="width:80px; height:80px;">
                            <i class="fas fa-building fa-2x text-primary"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="display-6 fw-bold mb-1">Welcome back, {{ Auth::user()->company->company_name ?? Auth::user()->name }}</h1>
                        <p class="lead opacity-90 mb-0">Manage your hiring pipeline in one place</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('employer.jobs.create') }}" class="btn btn-light btn-lg fw-bold px-4 shadow">
                    <i class="fas fa-plus me-2"></i> Post New Job
                </a>
            </div>
        </div>

        <!-- Quick Stats Tiles -->
        <div class="row g-4 mt-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center bg-white text-dark shadow-sm border-0">
                    <div class="card-body py-4">
                        <h3 class="fw-bold mb-1 text-primary">{{ $activeJobsCount }}</h3>
                        <p class="text-muted mb-0">Active Jobs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center bg-white text-dark shadow-sm border-0">
                    <div class="card-body py-4">
                        <h3 class="fw-bold mb-1 text-success">{{ $totalApplicants }}</h3>
                        <p class="text-muted mb-0">Total Applicants</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center bg-white text-dark shadow-sm border-0">
                    <div class="card-body py-4">
                        <h3 class="fw-bold mb-1 text-warning">{{ $interviewCount }}</h3>
                        <p class="text-muted mb-0">Interviews Scheduled</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center bg-white text-dark shadow-sm border-0">
                    <div class="card-body py-4">
                        <h3 class="fw-bold mb-1 text-info">{{ $hiredCount }}</h3>
                        <p class="text-muted mb-0">Hired Candidates</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">

    <!-- Recent Posted Jobs -->
    <div class="card shadow border-0 mb-5">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Your Recent Posted Jobs</h5>
            <a href="{{ route('employer.jobs.index') }}" class="btn btn-sm btn-outline-primary">
                View All <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="card-body p-0">
            @if($recentJobs->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No jobs posted yet</h5>
                    <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary mt-3">
                        Post Your First Job
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Job Title</th>
                                <th>Location</th>
                                <th>Posted On</th>
                                <th>Applicants</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentJobs as $job)
                                <tr>
                                    <td class="fw-medium">
                                        <a href="{{ route('employer.jobs.show', $job) }}" class="text-dark">
                                            {{ $job->title }}
                                        </a>
                                    </td>
                                    <td>{{ $job->location }}</td>
                                    <td>{{ $job->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $job->applications_count }}</span>
                                    </td>
                                    <td>
                                        @if($job->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($job->status === 'closed')
                                            <span class="badge bg-secondary">Closed</span>
                                        @elseif($job->status === 'pending')
                                            <span class="badge bg-warning">Pending Approval</span>
                                        @else
                                            <span class="badge bg-danger">Expired</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('employer.jobs.show', $job) }}" class="btn btn-sm btn-outline-primary me-1">
                                            View
                                        </a>
                                        <a href="{{ route('employer.jobs.edit', $job) }}" class="btn btn-sm btn-outline-secondary me-1">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Applicants Overview -->
    <div class="card shadow border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Recent Applicants</h5>
            <a href="#" class="btn btn-sm btn-outline-primary">
                View All Applicants <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="card-body p-0">
            @if($recentApplicants->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No applicants yet</h5>
                    <p class="text-muted">Applicants will appear here once candidates apply to your jobs.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Candidate</th>
                                <th>Applied For</th>
                                <th>Applied On</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApplicants as $application)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($application->user->profile_photo_path)
                                                <img src="{{ Storage::url($application->user->profile_photo_path) }}" 
                                                     alt="{{ $application->user->name }}" 
                                                     class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">{{ $application->user->name }}</div>
                                                <small class="text-muted">{{ $application->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('jobs.show', $application->jobPosting) }}" class="text-dark">
                                            {{ Str::limit($application->jobPosting->title, 40) }}
                                        </a>
                                    </td>
                                    <td>{{ $application->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $application->status === 'hired' ? 'success' : 
                                            $application->status === 'rejected' ? 'danger' : 
                                            $application->status === 'shortlisted' ? 'warning' : 'secondary'
                                        }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary me-1">
                                            View Profile
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            Update Status
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection