<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\PostPhoto;
use App\Models\PostProperty;
use App\Models\Crumb;
use Illuminate\Support\Facades\Auth;

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
    public function store(PostRequest $request)
    {
        $user_id                = Auth::id();
        $params                 = $request->validated();
        $params['user_id']      = $user_id;
        $params['slug']         = makeSlug($request->input('name'));
        $params['price']        = $request->input('price');

        $post                   = Post::create($params);

        if ($post) {
            if ($request->input('photos')) {
                PostPhoto::createByParams($post->id, $request->input('photos'));
            }

            if ($request->input('properties')) {
                PostProperty::createByParams($post->id, $request->input('properties'));
            }

            $data = [
                'post' => $post,
            ];
            return response(['data' => $data]);
        } else {
            return response(['error' => "Cannot create post"]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post     = Post::find($id);

        if ($post) {
            $crumbs   = Crumb::crumbCategory($post->category_id);

            $post_properties = PostProperty::has('property')
                ->with('property.parent')
                ->where('post_id', $post->id)
                ->get();

            $post_photos = PostPhoto::where('post_id', $post->id)->get();

            $array_properties = [];
            foreach ($post_properties as $post_property) {
                if ($post_property->property->parent) {
                    $index = $post_property->property->parent->sorted ?? $post_property->property->parent_id;
                    $item = [
                        "id"        => $post_property->property_id,
                        "name"      => $post_property->property->parent->name,
                        "value"     => $post_property->property->name,
                        "sorted"    => $index
                    ];
                    $array_properties[] = $item;
                }
            }
            return response(['data' => [
                'post'              => $post,
                'crumbs'            => $crumbs,
                'post_properties'   => $array_properties,
                'post_photos'       => $post_photos,
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
        $params                 = $request->validated();
        $params['slug']         = makeSlug($request->input('name'));
        $params['price']        = $request->input('price');

        $post                   = Post::find($id);

        if ($post) {
            $post->update($params);
            return response(['data' => ['post' => $post]]);
        } else {
            return response(['error' => 'Post not found'], 400);
        }
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
