@extends('master')

@section('content')

<!-- Blog Entries Column -->
      <div class="col-md-8">
          <br><br>
          <h3>Login Page</h3>

          <form method="POST" action="/login">
              {{csrf_field()}}
                            
              <div class="form-group">
                  <label for="email">E-mail</label>
                  <input type="text" name="email" value="{{ old('email') }}" class="form-control form-app" placeholder="Email Address">
              </div>
              
              <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" name="password" value="{{ old('name') }}" class="form-control form-app" placeholder="Password">
              </div>
              
              <div class="form-group">
                  <button type="submit" class="btn btn-primary">Login</button>
              </div>
              
          </form>
          
          <div>
              @foreach($errors->all() as $error)
              {{$error}}
              @endforeach
          </div>
                    
      </div>

@stop