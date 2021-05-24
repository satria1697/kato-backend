<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Models\Data\Article;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends BaseController
{
    private $rules = [
        'title' => 'required|string|min:5',
        'brief' => 'required|string|min:5',
        'text' => 'required|string|min:5',
        'show' => 'required|boolean',
        'image' => 'starts_with:data:image/|nullable'
    ];

    public function index(Request $request) {
        $input = $request->all();
        $article = Article::query();
        $search = in_array('search', $input);
        if ($search) {
            $article->where('title', 'like', '%'.$input['search'].'%');
        }
        $data = $article->get();
        foreach ($data as $da) {
            if ($da['image']) {
                $da['image'] = $this->imgToBase64($da['image']);
            }
        }
        return $this->sendResponse($data, "success-get");
    }

    public function show($slug) {
        $data = Article::where('slug', $slug)->first();
        if (!$data) {
            $this->sendError('not-found');
        }
        if ($data['image']) {
            $data['image'] = $this->imgToBase64($data['image']);
        }
        return $this->sendResponse($data, "success-get");
    }

    public function store(Request $request) {
        $validate = $this->validateData($request->all(), $this->rules);

        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 200);
        }

        $base64 = $request['image'];
        $path = $this->base64ToImg('article', $base64);
        $article = new Article();
        $article['title'] = $request['title'];
        $article['brief'] = $request['brief'];
        $article['text'] = $request['text'];
        $article['show'] = $request['show'];
        $article['image'] = $path;
        $article['slug'] = $this->sluging($request['title']).'-'.$this->unixNow();
        if (!$article->save()) {
            return $this->sendError("error-get");
        }
        return $this->sendResponse(true, 'success');
    }

    public function update(Request $request, $slug) {
        $validate = $this->validateData($request->all(), $this->rules);

        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 200);
        }

        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            return $this->sendError('not-found');
        }
        $base64 = $request['image'];
        $path = $this->base64ToImg('article', $base64);
        $article['title'] = $request['title'];
        $article['brief'] = $request['brief'];
        $article['text'] = $request['text'];
        $article['show'] = $request['show'];
        $article['image'] = $path;
        if (!$article->save()) {
            return $this->sendError('error-update');
        }
        return $this->sendResponse(true, 'success');
    }

    public function remove(Request $request, $id) {
        $article = Article::find($id);
        if (!$article) {
            $this->sendError('not-found');
        }
        if (!$article->delete()) {
            $this->sendError('cant-delete');
        }
        return $this->sendResponse(true, 'success');
    }

    public function sluging($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
