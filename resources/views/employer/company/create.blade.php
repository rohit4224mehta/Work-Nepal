@extends('layouts.app')

@section('title','Create Company')

@section('content')

<div class="max-w-3xl mx-auto">

<h1 class="text-2xl font-bold mb-6">
Create Company Profile
</h1>

<form method="POST" action="{{ route('employer.company.store') }}">

@csrf

<div class="mb-4">
<label class="block mb-1">Company Name</label>
<input type="text" name="name" class="w-full border rounded p-3" required>
</div>

<div class="mb-4">
<label class="block mb-1">Industry</label>
<input type="text" name="industry" class="w-full border rounded p-3">
</div>

<div class="mb-4">
<label class="block mb-1">Location</label>
<input type="text" name="location" class="w-full border rounded p-3">
</div>

<div class="mb-4">
<label class="block mb-1">Website</label>
<input type="url" name="website" class="w-full border rounded p-3">
</div>

<div class="mb-6">
<label class="block mb-1">Company Description</label>
<textarea name="description" rows="4" class="w-full border rounded p-3"></textarea>
</div>

<button class="bg-red-600 text-white px-6 py-3 rounded">
Create Company
</button>

</form>

</div>

@endsection