<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmbeddableController extends Controller
{
    public function download(Request $request)
    {
        $title = $request->input('title');
        $query = \http_build_query([
            'url' => route('api.exports.embeddable.view', [
                'url' => $request->input('url'),
                'title' => $title
            ]),
            'nombre' => $title.'_'.Carbon::now()->format('d-m-Y'),
            'download' => 'true',
            'footer' => 'Auto Seguro 365 - '.Carbon::now()->year,
        ]);

        $url = config('url.render')."/raw?$query";

        return redirect($url);
    }

    public function view(Request $request)
    {
        return view('export.embeddable', [
            'url' => urldecode($request->input('url')),
            'title' => $request->input('title')
        ]);
    }
}