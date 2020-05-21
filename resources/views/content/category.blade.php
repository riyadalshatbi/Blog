@extends('master')



@section('content')

<!-- Blog Entries Column -->
      <div class="col-md-8">

        <h1 class="my-4">Welcome To Riyad Blog
          <small>All Posts here</small>
        </h1>

        <!-- Blog Post -->
          @foreach($posts as $post)
        <div class="card mb-4">
          <div class="card-body">
            <h2 class="card-title"><a href="/posts/{{$post->id}}">{{$post->title}}</a></h2>              
              <span class="glyphicon glyphicon-time text-muted">Posted on {{ $post->created_at }}</span>
              <br><br>
              
              @if($post->url)
              <img class="card-img-top" src="../upload/{{$post->url}}" alt="Card image cap">
              @endif
              
              <br><br>
            <p class="card-text">{{$post->body}}</p>
            <a href="/posts/{{$post->id}}" class="btn btn-primary">Read More &rarr;</a>
          </div>
          <div class="card-footer text-muted">
              <span class="glyphicon glyphicon-time"></span>
            Comments
          </div>
        </div>
          @endforeach
          
          <div>
              @foreach($errors->all() as $error)
                {{$error}} <br>
              @endforeach
          </div>
                  
      </div>

@stop