<x-admin.layout>

    @section('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.css" />
    @endsection

    <div class="card shadow-lg">
        <div class="card-body">
            <form id="createFaqForm">
                @csrf
                <div class="form-group">
                    <label for="question">Question</label>
                    <input type="text" class="form-control" id="question" value="{{$faq->question}}" disabled>
                </div>
                <div class="form-group">
                    <label for="answer">Answer</label>
                    <textarea class="form-control" id="answer" name="answer">
                        {!! $faq->answer !!}
                    </textarea>
                </div>
                <div class="text-right mt-4">
                    <a href="{{route('faq.index')}}">
                        <button type="button" class="btn btn-secondary">Kembali</button>
                    </a>
                </div>
            </form>
        </div>
    </div>


    {{-- Include CKEditor --}}
    @section('script')
    <script type="importmap">
        {
			"imports": {
				"ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.js",
				"ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.1/"
			}
		}
	</script>

    <script type="module">
        import {
            ClassicEditor,
            AccessibilityHelp,
            Alignment,
            Autoformat,
            AutoLink,
            Autosave,
            BalloonToolbar,
            BlockQuote,
            Bold,
            CloudServices,
            Code,
            CodeBlock,
            Essentials,
            FindAndReplace,
            GeneralHtmlSupport,
            Heading,
            Highlight,
            HorizontalLine,
            HtmlEmbed,
            ImageBlock,
            ImageInline,
            ImageInsert,
            ImageInsertViaUrl,
            ImageResize,
            ImageStyle,
            ImageToolbar,
            ImageUpload,
            Indent,
            IndentBlock,
            Italic,
            Link,
            List,
            ListProperties,
            Paragraph,
            SelectAll,
            SimpleUploadAdapter,
            SpecialCharacters,
            SpecialCharactersArrows,
            SpecialCharactersCurrency,
            SpecialCharactersEssentials,
            SpecialCharactersLatin,
            SpecialCharactersMathematical,
            SpecialCharactersText,
            Strikethrough,
            Style,
            Table,
            TableCellProperties,
            TableProperties,
            TableToolbar,
            TextTransformation,
            TodoList,
            Underline,
            Undo
        } from 'ckeditor5';

        const editorConfig = {
            plugins: [
                AccessibilityHelp,
                Alignment,
                Autoformat,
                AutoLink,
                Autosave,
                BalloonToolbar,
                BlockQuote,
                Bold,
                CloudServices,
                Code,
                CodeBlock,
                Essentials,
                FindAndReplace,
                GeneralHtmlSupport,
                Heading,
                Highlight,
                HorizontalLine,
                HtmlEmbed,
                ImageBlock,
                ImageInline,
                ImageInsert,
                ImageInsertViaUrl,
                ImageResize,
                ImageStyle,
                ImageToolbar,
                ImageUpload,
                Indent,
                IndentBlock,
                Italic,
                Link,
                List,
                ListProperties,
                Paragraph,
                SelectAll,
                SimpleUploadAdapter,
                SpecialCharacters,
                SpecialCharactersArrows,
                SpecialCharactersCurrency,
                SpecialCharactersEssentials,
                SpecialCharactersLatin,
                SpecialCharactersMathematical,
                SpecialCharactersText,
                Strikethrough,
                Style,
                Table,
                TableCellProperties,
                TableProperties,
                TableToolbar,
                TextTransformation,
                TodoList,
                Underline,
                Undo
            ],
        };

        ClassicEditor.create(document.querySelector('#answer'), editorConfig)
            .then(editor => {
                editor.enableReadOnlyMode('answer')
            }).catch(err => {
                console.error(err)
            });
    </script>
    @endsection

</x-admin.layout>