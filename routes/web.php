<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\userController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\PostCommentsController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\ForgotPasswordController;
use App\Models\News;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;





///////////////////////// media yuklemek, silmek , komputere hemin sekli yuklemek //////////////////////
    Route::get('/mediayukle/media{id}', [MainController::class, 'mediadownload'])->name('download');
    Route::get('/mediayukle/delete{id}', [MainController::class, 'destroy'])->name('destroy');
    Route::get('/mediayukle', [MainController::class, 'index2'])->name('index2');
    Route::post('/mediayukle', [MainController::class, 'store'])->name('store');
    ///////////////////////////////////////////////////////////////////////////////////////




    //-------------- BLOG WEBSITE -------------------------------//
   Route::redirect('/', 'en');

   Route::group([
    'prefix' => '{language}',
    'where' => ['language => (en|az)'],
   ], function(){

        Route::get('/', [userController::class, 'main'])->name('main');
        Route::get('/about', [userController::class, 'about'])->name('about');
        Route::get('/services', [userController::class, 'services'])->name('services');
        Route::get('/projects', [userController::class, 'projects'])->name('projects');
        Route::get('/blog', [PostsController::class, 'index'])->name('blog');
        Route::get('/contact', [userController::class, 'contact'])->name('contact');
        Route::get('/testimonial', [userController::class, 'testimonial'])->name('testimonial');

        //contact send mail
        Route::post('/mail', [userController::class, 'mail'])->name('mail');
        //newsletter
        Route::post('/letter', [userController::class, 'letter'])->name('letter');

        //show Login
        Route::get('/login', [userController::class, 'showlogin'])->middleware('guest')->name('showlogin');
        Route::post('/login', [userController::class, 'authenticate'])->middleware('guest')->name('login');

        //show register/Create Form
        Route::get('/register',[userController::class, 'create']) ->middleware('guest')->name('showregister');
        //Create New User
        Route::post('/users', [userController::class,'store'])->middleware('guest')->name('createuser');

        //Create LogOut
        Route::post('/logout', [userController::class, 'logout'])->middleware('auth')->name('logout');

       // Route::post('/blog/posts/{post}/comments', function (App\Models\Post $post){
      //      $post->addComment([
        //        'body' => request('body'),
       //         'parent_id'=> request('parent_id', null)
        //    ]);
      //      return back();

      //  });

     //   route::get('/blog/posts/comments', function($language, Post $post){

       //     return view('show', [
       //         'post' => $post
       //     ]);
       // });

        Route::post('/blog/posts/{post:slug}/comments', [PostCommentsController::class, 'store'])->name('postcomment');
        Route::post('/reply/store', [PostCommentsController::class, 'replyStore'])->name('reply.add');
        Route::delete('/blog/posts/{comment}/comments/', [PostCommentsController::class, 'destroycomment'])->name('destroycomment');
        Route::patch('/blog/posts/comments/{comment}', [PostCommentsController::class, 'editcomment'])->name('commentedit');
        Route::get('/blog/posts/{comment}/edit', [PostCommentsController::class, 'showcommentedit'])->name('showcommentedit');



        Route::post('/testimonial/comments', [PostCommentsController::class, 'store']); //baxarsan


        Route::get('/blog/posts/{post:slug}',[PostsController::class, 'show'])->name('getpostslug');

        Route::get('/blog/categories/{category:slug}',[PostsController::class, 'index'])->name('getcategoryslug');

        Route::get('/blog/authors/{author:username}',[PostsController::class, 'authorindex'])->name('getauthorusername');

       // Route::get('/blog/categories/{category:slug}', function ($language, Category $category){
         //      return view ('webpage.blog' , [

          //        'posts' => $category->posts,
         //           'currentCategory' => Category::firstWhere('slug', request('category')),
         //           'categories' => Category::all()
          //      ]);

     //   })->name('getcategoryslug');

       // Route::get('/blog/authors/{author:username}', function ($language, User $author){
       //         return view ('webpage.blog' , [
          //          'posts' => $author->posts,
        //            'categories' => Category::all()


           //     ]);

     //   })->name('getauthorusername');

        //forgotpassword
        Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
        Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
        Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
        Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

        //admin panel
        Route::middleware('can:admin')->group(function(){
            // resource adminpostcontrollerin icindeki get post ve s. birbasa burdan oxuyur..
            //Route::resource('/admin/posts', AdminPostController::class); // except funksiyasin da islede bilerdin
                Route::get('/admin/posts/create', [AdminPostController::class, 'create'])->name('showpostcreate');
                Route::post('/admin/posts', [AdminPostController::class, 'store'])->name('adminpost');
                Route::get('/admin/posts', [AdminPostController::class, 'index'])->name('showpost');
                Route::get('/admin/posts/{post:slug}/edit', [AdminPostController::class, 'edit'])->name('editpost');
                Route::patch('/admin/posts/{post}', [AdminPostController::class, 'update'])->name('patchpost');
                Route::delete('/admin/posts/{post}', [AdminPostController::class, 'destroy'])->name('deletepost');
          });

        //Profile edit

        Route::get('/profile/edit', [userController::class, 'editprofile'])->name('showeditprofile');
        Route::patch('/profile/{user}', [userController::class, 'submiteditprofile'])->name('editprofile');

          Route::get('/blog/posts/{post:slug}/edit', [PostsController::class, 'showpostedit'])->name('showeditpost');
          Route::patch('/blog/posts/{post}', [PostsController::class, 'postedit'])->name('userpatchpost');
          Route::get('/blog/posts', [PostsController::class , 'showpost'])->name('showcreatepost');
          Route::post('/blog/posts', [PostsController::class, 'createpost'])->name('usercreatepost');
          Route::delete('/blog/posts/{post}', [PostsController::class , 'deletepost'])->name('destroypost');






          Route::get('/postyarat/create', [PostsController::class, 'create'])->name('posts.create');
          Route::post('/postyarat/create', [PostsController::class, 'storee'])->name('posts.storee');
          Route::get('/postyarat/show', [PostsController::class, 'indexx'])->name('posts.index');
          Route::get('/postyarat/show/{post}', [PostsController::class, 'showw'])->name('posts.show');
          Route::post('/postyarat/comment', [PostsController::class, 'storee'])->name('comments.store');


    });


