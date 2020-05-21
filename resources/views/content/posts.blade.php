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
              
              <span class="glyphicon glyphicon-time text-muted">Posted on {{ $post->created_at->toDayDateTimeString() }} - 
              <strong>Category: <a href="../category/{{$post->category->name}}">{{$post->category->name}}</a></strong></span><br><br>
              @if($post->url)
              <img class="card-img-top" src="upload/{{$post->url}}" alt="Card image cap">
              @endif
              <br><br>
                <p class="card-text">{{$post->body}}</p>
                <a href="/posts/{{$post->id}}" class="btn btn-primary">Read More &rarr;</a>
              @php
              $like_count = 0;
              $dislike_count = 0;
              
              $like_status = "btn-secondry";
              $dislike_status = "btn-secondry";
              @endphp
              
              @foreach($post->likes as $like)
              
              @php
              if($like->like == 1)

                $like_count++;

              if($like->like == 0)

                $dislike_count++;

              
              if(Auth::check())
              {
                  if($like->like == 1 && $like->user_id == Auth::user()->id)

                    $like_status = "btn-success";

                  if($like->like == 0 && $like->user_id == Auth::user()->id)

                    $dislike_status = "btn-danger";
              }

              @endphp
              
              @endforeach
              
              <button data-like="{{$like_status}}" data-postid="{{ $post->id }}_l" type="button" class="like btn {{$like_status}}">Like 
                  <span class="glyphicon glyphicon-thumbs-up"> <b><span class="like_count">{{ $like_count }}</span></b></span>
              </button>
              <button type="button" data-postid="{{ $post->id }}_d" class="dislike btn {{$dislike_status}}">Dislike 
                  <span class="glyphicon glyphicon-thumbs-down"> <b><span class="dislike_count">{{ $dislike_count }}</span></b></span>
              </button>
          </div>
          <div class="card-footer text-muted">
              <span class="glyphicon glyphicon-time"></span>
            Comments
          </div>
        </div>
          @endforeach
          
          @if(Auth::check())
          @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Editor'))    
          <form method="POST" action="/posts/store" enctype="multipart/form-data">
              {{csrf_field()}}
              <div class="form-group">
                  <label for="title">Title:</label>
                  <input type="text" name="title" id="title" class="form-control">
              </div>
              <div class="form-group">
                  <label for="body">Body:</label>
                  <textarea name="body" id="body" class="form-control"></textarea>
              </div>
              <div class="form-group">
                  <label for="url">Image:</label>
                  <input type="file" name="url" id="url">
              </div>
              <div class="form-group">
                  <button type="submit" class="btn btn-primary">Add Post</button>
              </div>
          </form>
          @endif
          @endif

          <div>
              @foreach($errors->all() as $error)
                {{$error}} <br>
              @endforeach
          </div>            

      </div>

@stop
