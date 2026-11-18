<?php

namespace Pondol\Editor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SampleController extends Controller
{
    public function show(?string $template = null)
    {
        $template = $template ?? config('pondol-editor.default-template', 'suneditor');
        abort_unless(view()->exists("editor::editor.{$template}.editor"), 404);

        return view('editor::sample', ['template' => $template]);
    }

    public function store(Request $request, string $template)
    {
        return response()->json([
            'template' => $template,
            'payload' => $request->all(),
        ]);
    }
}
