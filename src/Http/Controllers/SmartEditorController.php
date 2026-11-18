<?php

namespace Pondol\Editor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Pondol\Editor\Traits\SmartEditor;

class SmartEditorController extends Controller
{
    use SmartEditor;

    public function create()
    {
        return view('editor::sample', [
            'template' => 'smart-editor',
            'action' => route('editor.smarteditor'),
            'fieldName' => 'ir1',
            'fieldId' => 'ir1',
            'value' => '<p>서버의 데이타는 이렇게 호출됩니다</p>',
        ]);
    }

    public function store(Request $request)
    {
        return response()->json($request->all());
    }

    public function upload()
    {
        return view('editor::editor.smart-editor.photo-upload', []);
    }

    public function uploadStore(Request $request)
    {
        $result = $this->_uploadStore($request);
        if (! empty($result['error'])) {
            return redirect($result['error']);
        }

        return redirect($result['url']);
    }

    public function uploadStoreHtml5(Request $request)
    {
        return $this->_uploadStoreHtml5($request);
    }
}
