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
    public function index(Request $request)
    {
        $search = $request->search;
        $split = explode(':', $search);
        $searchby = 'title';

        if (count($split) > 1) {
            $searchby = $split[0];
            $search = $split[1];
        }

        $article = Article::query();
        if ($search) {
            $article->where($searchby, 'like', '%'.$search.'%');
        }
        if ($search && ($searchby === "title" || $searchby === "text" || $searchby === "brief")) {
            $article->orWhere($searchby.'_id', 'like', '%'.$search.'%');
        }

        $data = $article->get();
        foreach ($data as $da) {
            if ($da['image']) {
                $da['image'] = $this->imgToBase64($da['image']);
            }
        }
        return $this->sendResponse($data, "success-get");
    }

    public function show($slug)
    {
        $data = Article::where('slug', $slug)->first();
        if (!$data) {
            $this->sendError('not-found');
        }
        if ($data['image']) {
            $data['image'] = $this->imgToBase64($data['image']);
        }
        return $this->sendResponse($data, "success-get");
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|min:5',
            'title_id' => 'required|string|min:5',
            'brief' => 'required|string|min:5',
            'brief_id' => 'required|string|min:5',
            'text' => 'required|string|min:5',
            'text_id' => 'required|string|min:5',
            'show' => 'required|boolean',
            'image' => 'starts_with:data:image/|nullable'
        ];

        $input = $request->all();
        $validate = $this->validateData($input, $rules);
        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 422);
        }

        $base64 = $request['image'];
        $path = $this->base64ToImg('article', $base64);
        $article = new Article();
        $article['title'] = $request['title'];
        $article['title_id'] = $request['title_id'];
        $article['brief'] = $request['brief'];
        $article['brief_id'] = $request['brief_id'];
        $article['text'] = $request['text'];
        $article['text_id'] = $request['text_id'];
        $article['show'] = $request['show'];
        $article['image'] = $path;
        $article['slug'] = $this->sluging($request['title']).'-'.$this->unixNow();
        if (!$article->save()) {
            return $this->sendError("error-get");
        }
        return $this->sendResponse(true, 'success');
    }

    public function update(Request $request, $slug)
    {
        $rules = [
            'title' => 'required|string|min:5',
            'title_id' => 'required|string|min:5',
            'brief' => 'required|string|min:5',
            'brief_id' => 'required|string|min:5',
            'text' => 'required|string|min:5',
            'text_id' => 'required|string|min:5',
            'show' => 'required|boolean',
            'image' => 'starts_with:data:image/|nullable',
            'slug' => 'required|string|min:1',
        ];

        $input = $request->all();
        $input['slug'] = $slug;
        $input = $request->all();
        $validate = $this->validateData($input, $rules);
        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 422);
        }

        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            return $this->sendError('not-found');
        }
        $base64 = $request['image'];
        $path = $this->base64ToImg('article', $base64);
        $article['title'] = $request['title'];
        $article['title_id'] = $request['title_id'];
        $article['brief'] = $request['brief'];
        $article['brief_id'] = $request['brief_id'];
        $article['text'] = $request['text'];
        $article['text_id'] = $request['text_id'];
        $article['show'] = $request['show'];
        $article['image'] = $path;
        if (!$article->save()) {
            return $this->sendError('error-update');
        }
        return $this->sendResponse(true, 'success');
    }

    public function remove(Request $request, $id)
    {
        $article = Article::find($id);
        if (!$article) {
            $this->sendError('not-found');
        }
        if (!$article->delete()) {
            $this->sendError('cant-delete');
        }
        return $this->sendResponse(true, 'success');
    }

    public function sluging($text)
    {
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
