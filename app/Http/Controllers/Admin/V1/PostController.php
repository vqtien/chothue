<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Crumb;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit          = $request->input('limit') ?? 20;
        $user_id        = $request->input('user_id');
        $category_id    = $request->input('category_id');
        $province_id    = $request->input('province_id');
        $district_id    = $request->input('district_id');
        $ward_id        = $request->input('ward_id');
        $postModel      = Post::orderBy('created_at', 'DESC');

        if ($category_id) {
            $postModel = $postModel->where('category_id', $category_id);
        }

        if ($user_id) {
            $postModel = $postModel->where('user_id', $user_id);
        }

        if ($province_id) {
            $postModel = $postModel->where('province_id', $province_id);
        }
        if ($district_id) {
            $postModel = $postModel->where('district_id', $district_id);
        }
        if ($ward_id) {
            $postModel = $postModel->where('ward_id', $ward_id);
        }

        $posts = $postModel->paginate($limit)
            ->appends($request->query());

        $data = [
            'posts' => $posts,
        ];

        return response(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post     = Post::find($id);
        $crumbs   = Crumb::crumbCategory($post->category_id);

        if ($post) {
            return response(['data' => [
                'post'      => $post,
                'crumbs'    => $crumbs
            ]]);
        } else {
            return response(['error' => 'Post not found'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if ($post) {
            $post->delete();
            return response(['message' => 'OK']);
        } else {
            return response(['error' => 'Post not found'], 400);
        }
    }
}
