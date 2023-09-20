<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;

class CommentController extends Controller
{
    /**
     * Bình luận theo đối tượng: Product, Article, Service,
     */
    public function index(Request $request)
    {
        $category       = $request->input("object_name");
        $model          = "App\Models\\$category";
        $commentable_id = $request->input('object_id');
        $limit          = $request->input('limit') ?? 10;
        $sort           = $request->input('sort_type') ?? "ASC";

        if (!$commentable_id && !$category) {
            return response(['data' => []]);
        }

        $comments = Comment::selectRaw("id, user_id, parent_id, content, created_at")
            ->with(['user', 'childrens.user'])
            ->where(["parent_id" => null, "commentable_id" => $commentable_id, "commentable_type" => $model])
            ->orderBy('created_at', $sort)
            ->paginate($limit);

        $data = [
            'comments' => $comments,
        ];

        return response(['data' => $data]);
    }

    /**
     * Bình luận theo user
     */
    public function comments(Request $request)
    {
        $user_id    = Auth::id();
        $limit      = $request->input('limit') ?? 10;
        $sort       = $request->input('sort_type') ?? "ASC";

        $comments = Comment::selectRaw(
            "id, 
            user_id, 
            content, 
            commentable_id,
            commentable_type,
            created_at
            "
        )->with('commentable:id,name,slug')
            ->where(["user_id" => $user_id])
            ->orderBy('created_at', $sort)
            ->paginate($limit);

        $data = [
            'comments' => $comments,
        ];

        return response(['data' => $data]);
    }
    /**
     * Lưu bình luận
     */
    public function store(Request $request)
    {
        $user   = User::find(Auth::id());

        $params = $request->all();

        $validator = Validator::make($params, [
            'content'           => 'required',
            'commentable_id'    => 'required',
            'commentable_type'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $params['user_id']          = $user->id;
        $commentable_type           = $params['commentable_type'];
        $params['commentable_type'] = "App\Models\\$commentable_type";

        $comment = Comment::create($params);

        $result = [
            "id"                => $comment->id,
            "user_id"           => $user->id,
            "parent_id"         => $comment->parent_id,
            "content"           => $comment->content,
            "created_at"        => $comment->created_at,
            "user" => [
                "id"        => $user->id,
                "name"      => $user->fullname,
                "email"     => $user->email,
                "avatar"    => $user->avatar
            ]
        ];

        return response(['data' => $result]);
    }
}
