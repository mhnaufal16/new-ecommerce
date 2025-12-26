@extends('layouts.admin')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Customers / Users</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Orders</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->type === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->type === 'vendor')
                                    <span class="badge bg-warning text-dark">Vendor</span>
                                @else
                                    <span class="badge bg-info">Customer</span>
                                @endif
                            </td>
                            <td>{{ $user->orders_count }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
