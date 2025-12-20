@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Store Settings</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-3">
                 <div class="list-group" id="settings-list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-general-list" data-bs-toggle="list" href="#list-general" role="tab" aria-controls="list-general">General</a>
                    <a class="list-group-item list-group-item-action" id="list-contact-list" data-bs-toggle="list" href="#list-contact" role="tab" aria-controls="list-contact">Contact Info</a>
                    <a class="list-group-item list-group-item-action" id="list-social-list" data-bs-toggle="list" href="#list-social" role="tab" aria-controls="list-social">Social Media</a>
                 </div>
                 <div class="mt-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Save All Changes
                    </button>
                 </div>
            </div>
            
            <div class="col-md-9">
                <div class="tab-content" id="nav-tabContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="list-general" role="tabpanel" aria-labelledby="list-general-list">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">General Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Store Name</label>
                                    <input type="text" class="form-control" name="general[store_name]" 
                                           value="{{ $settings['general']['store_name'] ?? config('app.name') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Store Tagline</label>
                                    <input type="text" class="form-control" name="general[store_tagline]" 
                                           value="{{ $settings['general']['store_tagline'] ?? 'Your trusted online store' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Currency Symbol</label>
                                    <input type="text" class="form-control" name="general[currency_symbol]" 
                                           value="{{ $settings['general']['currency_symbol'] ?? 'Rp' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="tab-pane fade" id="list-contact" role="tabpanel" aria-labelledby="list-contact-list">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Contact Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Support Email</label>
                                    <input type="email" class="form-control" name="contact[email]" 
                                           value="{{ $settings['contact']['email'] ?? 'support@ecommerce.com' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="contact[phone]" 
                                           value="{{ $settings['contact']['phone'] ?? '(021) 1234-5678' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" rows="3" name="contact[address]">{{ $settings['contact']['address'] ?? 'Jakarta, Indonesia' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="tab-pane fade" id="list-social" role="tabpanel" aria-labelledby="list-social-list">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Social Media Links</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Facebook URL</label>
                                    <input type="url" class="form-control" name="social[facebook]" 
                                           value="{{ $settings['social']['facebook'] ?? '#' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Instagram URL</label>
                                    <input type="url" class="form-control" name="social[instagram]" 
                                           value="{{ $settings['social']['instagram'] ?? '#' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Twitter/X URL</label>
                                    <input type="url" class="form-control" name="social[twitter]" 
                                           value="{{ $settings['social']['twitter'] ?? '#' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
