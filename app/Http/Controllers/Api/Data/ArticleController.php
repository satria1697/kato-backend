<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Models\Data\Article;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends BaseController
{
    public function index() {
        $data = Article::all();
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
        $decode = $this->checkJwt($request['jwt']);
        if ($decode['level'] > 2) {
            return $this->sendError('not-authorized');
        }
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
