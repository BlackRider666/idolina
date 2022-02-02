<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repo\DirectionRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    /**
     * @var DirectionRepo
     */
    private DirectionRepo $repo;

    public function __construct(DirectionRepo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse($this->repo->search($request->all()));
    }
}
