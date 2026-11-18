@extends('editor::layout.blank')

@section('content')
    @php
        $template = $template ?? config('pondol-editor.default-template', 'suneditor');
        $action = $action ?? route('editor.sample.store', ['template' => $template]);
        $fieldName = $fieldName ?? 'content';
        $fieldId = $fieldId ?? 'sample_editor';
        $sampleValue = $value ?? '<p>테스트 내용입니다.</p>';
    @endphp

    <div style="max-width: 960px; margin: 30px auto; padding: 0 16px;">
        <h3 style="margin-bottom: 12px;">Editor Sample: <code>{{ $template }}</code></h3>

        <form method="post" action="{{ $action }}">
            @csrf
            @include('editor::default', [
                'template' => $template,
                'name' => $fieldName,
                'id' => $fieldId,
                'value' => $sampleValue,
            ])

            <div style="margin-top: 16px;">
                <button type="submit">서버로 내용 전송</button>
            </div>
        </form>
    </div>
@endsection
