@extends('master')



@section('content')

<!-- Blog Entries Column -->
      <div class="col-md-8">

        <h1 class="my-4">Welcome To Riyad Blog
          <small>For Read More here</small>
        </h1>

        <!-- Blog Post -->
          
        <div class="card mb-4">
          <div class="card-body">
            <h2 class="card-title"><a href="/posts/{{$post->id}}">{{$post->title}}</a></h2>              
              <span class="glyphicon glyphicon-time text-muted">Posted on {{ $post->created_at}}</span>
              <br><br>
              @if($post->url)
              <img class="card-img-top" src="../upload/{{$post->url}}" alt="Card image cap">
              @endif
              <br><br>
            <p class="card-text">{{$post->body}}</p>              
                    
                    <div class="card-footer text-muted">
                        Comments                           
                    </div>
              <br>
              
                <div class="card-footer text-muted comments">
                        
                    @foreach($post->comments as $comment)
                    <p class="comment">{{$comment->body}}</p>
                    @endforeach                                        
                </div>
              
              <br>
              
              @if($stop_comment == 1)
              
              <h3>Oops!! Comments Are Currently Closed!!</h3>
              
              @else
              
            <form method="POST" action="/post/{{$post->id}}/store">
              {{csrf_field()}}
                @if(Auth::check())
                @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Editor') || Auth::user()->hasRole('User'))
              <div class="form-group">
                  <label for="body">Write Something . . .</label>
                  <textarea name="body" id="body" class="form-control"></textarea>
              </div>              
              <div class="form-group">
                  <button type="submit" class="btn btn-primary">Add Comment</button>
              </div>
                @endif
                @endif
          </form>
              
              @endif
              
          </div>
          
        </div>
          


      </div>

@stop