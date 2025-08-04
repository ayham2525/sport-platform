@extends('layouts.app')
@php use App\Helpers\PermissionHelper; @endphp
@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.users') }}</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="la la-dashboard mr-1"></i> {{ __('titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">
                <i class="la la-users mr-1"></i> {{ __('titles.users') }}
            </span>
        </li>
    </ul>
@endsection


@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container">

            <div class="card card-custom gutter-b">
                @if (session('success'))
                    <div class="alert alert-custom alert-success alert-dismissible fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="la la-check-circle"></i></div>
                        <div class="alert-text">{{ __( session('success')) }}</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-times"></i></span>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-custom alert-danger alert-dismissible fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="la la-exclamation-circle"></i></div>
                        <div class="alert-text">{{ __( session('error')) }}</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-times"></i></span>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{ __('titles.users') }}
                            <span class="d-block text-muted pt-2 font-size-sm">{{ __('titles.user_management') }}</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        @if (PermissionHelper::hasPermission('update', App\Models\User::MODEL_NAME))
                            {{-- Only show the button if the user has permission to create new users --}}

                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary font-weight-bolder">
                                <span class="svg-icon svg-icon-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                        viewBox="0 0 24 24">
                                        <g fill="none" fill-rule="evenodd">
                                            <rect width="24" height="24" />
                                            <circle fill="#000000" cx="9" cy="15" r="6" />
                                            <path
                                                d="M8.8,7 C9.8,5.2 11.8,4 14,4 C17.3,4 20,6.7 20,10 C20,12.2 18.8,14.2 17,15.2 C17,10.6 13.4,7 9,7 C8.9,7 8.9,7 8.8,7 Z"
                                                fill="#000000" opacity="0.3" />
                                        </g>
                                    </svg>
                                </span>{{ __('titles.new_user') }}</a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    {{-- AJAX Search Form --}}
                    <form id="ajaxUserSearchForm" class="mb-5">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="name" class="form-control"
                                    placeholder="{{ __('columns.name') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="email" class="form-control"
                                    placeholder="{{ __('columns.email') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-control">
                                    <option value="">{{ __('columns.role') }}</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->slug }}">
                                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </form>


                    {{-- Table Container --}}
                    <div id="userTableContainer">
                        @include('users.partials.table', ['users' => $users])
                    </div>
                </div>
            </div>
        </div>
    </div>


@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const form = this.closest('form');

                    Swal.fire({
                        title: '{{ __('messages.confirm_delete_title') }}',
                        text: '{{ __('messages.confirm_delete_text') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e3342f',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '{{ __('messages.yes_delete') }}',
                        cancelButtonText: '{{ __('messages.cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed && form) {
                            form.submit();
                        }
                    });
                });
            });
        });
        let typingTimer;
        const doneTypingInterval = 500; // 0.5 second delay

        function loadUserData(params = {}) {
            $.ajax({
                url: '{{ route('admin.users.ajaxSearch') }}',
                method: 'POST',
                data: {
                    ...params,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    $('#userTableContainer').html(res.html);
                },
                error: function() {
                    alert("Failed to load data.");
                }
            });
        }

        // Trigger on typing (with delay)
        $('#ajaxUserSearchForm input, #ajaxUserSearchForm select').on('keyup change', function() {

            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                const formData = $('#ajaxUserSearchForm').serializeArray();
                const data = {};
                formData.forEach(item => data[item.name] = item.value);

                // Only trigger if at least one field has 3+ chars or role is selected
                if (
                    (data.name && data.name.length >= 3) ||
                    (data.email && data.email.length >= 3) ||
                    (data.role && data.role !== '')
                ) {
                    loadUserData(data);
                } else if (!data.name && !data.email && !data.role) {
                    // If all empty, load default (index) data
                    loadUserData();
                }
            }, doneTypingInterval);
        });

        // AJAX pagination
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            const formData = $('#ajaxUserSearchForm').serializeArray();
            const data = {
                page
            };
            formData.forEach(item => data[item.name] = item.value);
            loadUserData(data);
        });

        // Initial load
        $(document).ready(function() {
            loadUserData();
        });
    </script>
    @endpush
@endsection
