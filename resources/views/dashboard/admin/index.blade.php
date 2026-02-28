@extends('layouts.app')

@section('title', 'Admin Dashboard - WorkNepal')

@section('content')

<!-- Top Stats Row -->
<div class="bg-dark text-white py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark border-0 shadow-sm text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-white-75 mb-1">Total Users</h6>
                                <h3 class="fw-bold mb-0">{{ $stats['total_users'] }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-25 rounded p-3">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                        <small class="text-success fw-medium mt-2 d-block">
                            <i class="fas fa-arrow-up me-1"></i> {{ $stats['new_users_today'] }} new today
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark border-0 shadow-sm text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-white-75 mb-1">Active Jobs</h6>
                                <h3 class="fw-bold mb-0">{{ $stats['active_jobs'] }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-25 rounded p-3">
                                <i class="fas fa-briefcase fa-2x text-primary"></i>
                            </div>
                        </div>
                        <small class="text-primary fw-medium mt-2 d-block">
                            <i class="fas fa-arrow-up me-1"></i> {{ $stats['new_jobs_today'] }} posted today
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark border-0 shadow-sm text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-white-75 mb-1">Pending Jobs</h6>
                                <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending_jobs'] }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-25 rounded p-3">
                                <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                            </div>
                        </div>
                        <small class="text-warning fw-medium mt-2 d-block">
                            Needs review
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-dark border-0 shadow-sm text-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-white-75 mb-1">Total Applications</h6>
                                <h3 class="fw-bold mb-0">{{ $stats['total_applications'] }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-25 rounded p-3">
                                <i class="fas fa-file-alt fa-2x text-info"></i>
                            </div>
                        </div>
                        <small class="text-info fw-medium mt-2 d-block">
                            <i class="fas fa-arrow-up me-1"></i> {{ $stats['new_applications_today'] }} today
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">

    <!-- Pending Jobs Queue (Priority Section) -->
    <div class="card shadow border-0 mb-5">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-exclamation-triangle me-2"></i> Pending Job Postings ({{ $pendingJobs->count() }})
            </h5>
            <a href="{{ route('admin.jobs.pending') }}" class="btn btn-sm btn-dark">
                View All Pending <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="card-body p-0">
            @if($pendingJobs->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-check-circle fa-4x mb-3"></i>
                    <h5>No pending jobs at the moment</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Job Title</th>
                                <th>Company</th>
                                <th>Posted By</th>
                                <th>Posted On</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingJobs as $job)
                                <tr>
                                    <td class="fw-medium">
                                        {{ $job->title }}
                                        @if($job->fresher_friendly)
                                            <span class="badge bg-success-subtle text-success ms-2">Fresher</span>
                                        @endif
                                    </td>
                                    <td>{{ $job->company->company_name }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($job->company->user->profile_photo_path)
                                                <img src="{{ Storage::url($job->company->user->profile_photo_path) }}" 
                                                     alt="" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                            @endif
                                            <span>{{ $job->company->user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $job->created_at->diffForHumans() }}</td>
                                    <td>{{ $job->location }}</td>
                                    <td>
                                        <form action="{{ route('admin.jobs.moderate', $job) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-success me-1">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.jobs.moderate', $job) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Reports & Analytics Quick View -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">Platform Activity (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-4">
                            <h5 class="fw-bold text-primary">{{ $stats['new_users_week'] }}</h5>
                            <p class="text-muted small mb-0">New Registrations</p>
                        </div>
                        <div class="col-6 mb-4">
                            <h5 class="fw-bold text-success">{{ $stats['new_jobs_week'] }}</h5>
                            <p class="text-muted small mb-0">New Job Postings</p>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold text-info">{{ $stats['new_applications_week'] }}</h5>
                            <p class="text-muted small mb-0">New Applications</p>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold text-warning">{{ $stats['reports_week'] }}</h5>
                            <p class="text-muted small mb-0">New Reports</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('admin.jobs.pending') }}" class="btn btn-outline-primary btn-lg w-100 py-4">
                                <i class="fas fa-hourglass-half fa-2x mb-2"></i><br>
                                Moderate Pending Jobs
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="btn btn-outline-info btn-lg w-100 py-4">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                Manage Users
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="btn btn-outline-warning btn-lg w-100 py-4">
                                <i class="fas fa-flag fa-2x mb-2"></i><br>
                                View Reports
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="btn btn-outline-danger btn-lg w-100 py-4">
                                <i class="fas fa-ban fa-2x mb-2"></i><br>
                                Blocked Content
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection