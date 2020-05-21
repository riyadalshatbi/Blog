<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;
use DB;
use App\User;
use App\Role;
use App\Like;

class pagesController extends Controller
{   
    // Display Home Pages
    public function posts()
    {
        $posts = Post::all();
        return view('content.posts',compact('posts'));
    }
    
    // Display Single Post to Enable of Write Comments
    public function post(Post $post)
    {
        // Get Status of Comment
        $stop_comment = DB::table('settings')->where('name','stop_comment')->value('value');
        
        return view('content.post',compact('post','stop_comment'));
    }
    
    // Store New Post
    public function store(Request $request)
    {
        $this->validate(request(),[
            'title'=>'required',
            'body'=>'required',
            'url'=>'image|mimes:jpg,jpeg,gif,png|max:8192'
        ]);
        
        $img_name = time() . '.' . $request->url->getClientOriginalExtension();
        
        $post = new Post;
        $post->title = request('title');
        $post->body = request('body');
        $post->url = $img_name;
        $post->save();
                
        
        $request->url->move(public_path('upload') , $img_name);
        
        /*        
        Post::create([
            'title'=>request('title'),
            'body'=>request('body'),
            'url'=>request('url')
        ]);
        */
        
        /*
            other method
        Post::create(request()->all());
        */
        
        return redirect('/posts');
    }
    
    // Display Category
    public function category($name)
    {
        $cat = DB::table('categories')->where('name',$name)->value('id');
        
        $posts = DB::table('posts')->where('category_id',$cat)->get();
        
        return view('content.category',compact('posts'));
    }
    
    // Display All Users in admin Page
    public function admin()
    {
        // Get All Users
        $users = User::all();
        
        // Get Status of Comments
        $stop_comment = DB::table('settings')->where('name','stop_comment')->value('value');
        
        // Get Status of Register
        $stop_register = DB::table('settings')->where('name','stop_register')->value('value');
        
        return view('content.admin',compact('users','stop_comment','stop_register'));
    }
    
    // Change Roles Users
    public function addRole(Request $request)
    {
        $user = User::where('email',$request['email'])->first();
        $user->roles()->detach();
        
        if($request['role_user'])
        {
            $user->roles()->attach(Role::where('name','User')->first());
        }
        
        if($request['role_editor'])
        {
            $user->roles()->attach(Role::where('name','Editor')->first());
        }
        
        if($request['role_admin'])
        {
            $user->roles()->attach(Role::where('name','Admin')->first());
        }
        
        return redirect()->back();
    }
    
    // Display Editor Page
    public function editor()
    {
        return view('content.editor');
    }
    
    // Display Access Denied Message
    public function accessDenied()
    {
        return view('content.access-denied');
    }
    
    // Add Like To Post
    public function like(Request $request)
    {
        $like_s = $request->like_s;
        $post_id = $request->post_id;
        $change_like = 0;
        
        $like = DB::table('likes')->where('post_id',$post_id)->where('user_id',Auth::user()->id)->first();
        
        // Add New Like
        if(! $like)
        {
            $new_like = new Like;
            $new_like->post_id = $post_id;
            $new_like->user_id = Auth::user()->id;
            $new_like->like = 1;
            $new_like->save();
            
            // Return Result To JS File
            $is_like = 1;
        }
        // To Remove The Like
        elseif($like->like == 1)
        {
            DB::table('likes')->where('post_id',$post_id)->where('user_id',Auth::user()->id)->delete();
            
            // Return Result To JS File
            $is_like = 0;
        }
        // For Convert Dislike To Like
        elseif($like->like == 0)
        {
            DB::table('likes')->where('post_id',$post_id)->where('user_id',Auth::user()->id)->update(['like' => 1]);
            
            // Return Result To JS File
            $is_like = 1;
            $change_like = 1;            
        }
        
        $response = array(
            
            'is_like'     => $is_like,
            'change_like' => $change_like
        );
        
        return response()->json($response , 200);
    }
    
    // Add Dislike To Post
    public function dislike(Request $request)
    {
        $like_s = $request->like_s;
        $post_id = $request->post_id;
        $change_dislike = 0;
        
        $dislike = DB::table('likes')->where('post_id',$post_id)->where('user_id',Auth::user()->id)->first();
        
        // Add New Dislike
        if(! $dislike)
        {
            $new_like = new Like;
            $new_like->post_id = $post_id;
            $new_like->user_id = Auth::user()->id;
            $new_like->like = 0;
            $new_like->save();
            
            // Return Result To JS File
            $is_dislike = 1;
        }
        // To Remove The Dislike
        elseif($dislike->like == 0)
        {
            DB::table('likes')->where('post_id',$post_id)->where('user_id',Auth::user()->id)->delete();
            
            // Return Result To JS File
            $is_dislike = 0;
        }
        // For Convert Like To Dislike
        elseif($dislike->like == 1)
        {
            DB::table('likes')->where('post_id',$post_id)->where('user_id',Auth::user()->id)->update(['like' => 0]);
            
            // Return Result To JS File
            $is_dislike = 1;
            $change_dislike = 1;
        }
        
        $response = array(
            
            'is_dislike'     => $is_dislike,
            'change_dislike' => $change_dislike
        );
        
        return response()->json($response , 200);
    }
    
    // Display Statistics Pages
    public function statistics()
    {
        // Count of users , posts , comments
        $users = DB::table('users')->count();
        $posts = DB::table('posts')->count();
        $comments = DB::table('comments')->count();
        
        // Get Active Users in Most comments
        $most_comments = User::withCount('comments')->orderBy('comments_count','desc')->first();
        
        $likes_count_1 = DB::table('likes')->where('user_id',$most_comments->id)->count();
        
        $user_1_count = $most_comments->comments_count + $likes_count_1;

        // Get Active Users in Most likes
        $most_likes = User::withCount('likes')->orderBy('likes_count','desc')->first();
        
        $comments_count_2 = DB::table('comments')->where('user_id',$most_likes->id)->count();
        
        $user_2_count = $most_likes->likes_count + $comments_count_2;
        
        if($user_1_count > $user_2_count)
        {
            $active_user = $most_comments->name;
            $active_user_likes = $likes_count_1;
            $active_user_comments = $most_comments->comments_count;
        }
        else
        {
            $active_user = $most_likes->name;
            $active_user_likes = $most_likes->likes_count;
            $active_user_comments = $comments_count_2;
        }
        
        // Return Active User To Page
        $statistics = array(
            
            'users'                => $users,
            'posts'                => $posts,
            'comments'             => $comments,
            'active_user'          => $active_user,
            'active_user_likes'    => $active_user_likes,
            'active_user_comments' => $active_user_comments
        );

        return view('content.statistics',compact('statistics'));
    }
    
    // Enable or Disable Comments
    public function settings(Request $request)
    {
        // Enable Comment
        if($request->stop_comment)
        {
            DB::table('settings')->where('name','stop_comment')->update(['value' => 1]);
        }
        // Disable Comment
        else
        {
            DB::table('settings')->where('name','stop_comment')->update(['value' => 0]);
        }
        
        // Enable Register
        if($request->stop_register)
        {
            DB::table('settings')->where('name','stop_register')->update(['value' => 1]);
        }
        // Disable Register
        else
        {
            DB::table('settings')->where('name','stop_register')->update(['value' => 0]);
        }
        
        return redirect()->back();
    }
    
    
    
}