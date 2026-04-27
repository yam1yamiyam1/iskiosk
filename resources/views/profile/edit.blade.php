@extends('layouts.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Profile
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    <div class="col-12">
                        <div class="row row-cards">
                            <div class="card">
                                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                                    <div class="profile-pic-wrapper">
                                        <img class="rounded-circle mt-5 mb-2 profilepic" width="150px" height="150px"
                                            src="{{ Auth::user()->image ? asset('storage/users/' . Auth::user()->image) : asset('storage/users/p1.jpg') }}">
                                    </div>
                                    @php use Illuminate\Support\Str; @endphp

                                    <h2>{{ Auth()->user()->fname }} {{ Str::upper(Auth()->user()->mi) }}. {{ Auth()->user()->lname }}</h2>
                                    <h3>Account</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="row row-cards">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                            <div class="form-group row">
                                <div class="col-5">
                                    <label for="fname">First Name</label>
                                    <input type="text" class="form-control @error('fname') is-invalid @enderror" placeholder="Enter a First Name" value="{{$user->fname}}" id="fname{{$user->id}}" autofocus name="fname" required>
                                </div>
                                <div class="col-2">
                                    <label for="mi">MI</label>
                                    <input type="text" class="form-control @error('mi') is-invalid @enderror" placeholder="M.I." value="{{$user->mi}}" id="mi{{$user->id}}" name="mi" required>
                                </div>
                                <div class="col-5">
                                    <label for="lname">Last Name</label>
                                    <input type="text" class="form-control @error('lname') is-invalid @enderror" placeholder="Enter a Last Name" value="{{$user->lname}}" id="lname{{$user->id}}" name="lname" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email{{$user->id}}" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" placeholder="Enter an email" required autocomplete="email" >
                            </div>
                            <div class="form-group mt-5">
                                <label for="image">Profile Image</label>
                                <input id="image{{$user->id}}" type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror" name="image">
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <label for="password" class="control-label">Change Password (Optional)</label>
                                    <input type="password" class="form-control" id="password{{$user->id}}" name="password" placeholder="Enter a Password" autocomplete="new-password" minlength="8">
                                </div>
                                <div class="col-md-6">
                                    <label for="confirmpassword" class="control-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmpassword{{$user->id}}" name="password_confirmation" placeholder="Re-enter Password" minlength="8">
                                </div>
                            </div>
                            <div class="mt-5 text-center">
                                <button class="btn btn-primary profile-button" type="submit">Save Profile</button>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
