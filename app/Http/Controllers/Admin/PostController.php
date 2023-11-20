<?php

namespace App\Http\Controllers\Admin;

use App\DataTable\PostDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\CreatePostRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use App\Services\Admin\PostService;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(
        PostService $postService
    ) {
        $this->postService = $postService;
    }

    public function list(int $type, PostDataTable $dataTable)
    {
        return responseJsonData(true, $dataTable->setTypeId($type)->toArray());
    }

    public function create(int $type, CreatePostRequest $request)
    {
        $fieldNames = getFieldNames(getTypeById($type)->fields);
        $post = $this->postService->create($type, $request->only($fieldNames));
        return responseJsonData(true, ['post' => $post]);
    }

    public function edit(int $type, int $post, CreatePostRequest $request)
    {
        $fieldNames = getFieldNames(getTypeById($type)->fields);
        $postItem = $this->postService->update($type, $post, $request->only($fieldNames));
        return responseJsonData(true, ['post' => $postItem]);
    }

    public function get(int $type, int $post)
    {
        return responseJsonData(true, ['post' => $this->postService->getPost($type, $post)]);
    }

    public function delete(int $type, Post $post)
    {
        return responseJsonData(true, ['post' => $this->postService->delete($type, $post)]);
    }

    public function activeList(int $type, int $selectedId, PostRepository $postRepository)
    {
        return responseJsonData(true, ['posts' => $postRepository->getActiveList($type, [$selectedId])]);
    }
}
