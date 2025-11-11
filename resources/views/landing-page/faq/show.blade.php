<x-landing-page.layout>

    @section('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.css" />
    <style>
        /* Default CKEditor heading styles with !important */

        h1,
        .h1 {
            font-size: 2em !important;
            /* Typically around 32px */
            font-weight: bold !important;
            margin-top: 0.67em !important;
            margin-bottom: 0.67em !important;
            line-height: 1.2 !important;
        }

        h2,
        .h2 {
            font-size: 1.5em !important;
            /* Typically around 24px */
            font-weight: bold !important;
            margin-top: 0.83em !important;
            margin-bottom: 0.83em !important;
            line-height: 1.3 !important;
        }

        h3,
        .h3 {
            font-size: 1.17em !important;
            /* Typically around 18.72px */
            font-weight: bold !important;
            margin-top: 1em !important;
            margin-bottom: 1em !important;
            line-height: 1.4 !important;
        }

        h4,
        .h4 {
            font-size: 1em !important;
            /* Typically around 16px */
            font-weight: bold !important;
            margin-top: 1.33em !important;
            margin-bottom: 1.33em !important;
            line-height: 1.5 !important;
        }

        h5,
        .h5 {
            font-size: 0.83em !important;
            /* Typically around 13.28px */
            font-weight: bold !important;
            margin-top: 1.67em !important;
            margin-bottom: 1.67em !important;
            line-height: 1.6 !important;
        }

        h6,
        .h6 {
            font-size: 0.67em !important;
            /* Typically around 10.72px */
            font-weight: bold !important;
            margin-top: 2.33em !important;
            margin-bottom: 2.33em !important;
            line-height: 1.7 !important;
        }

        .ck-content {
            border: none !important;
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }

        .ck-content>ol {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }

        .ck-editor__top {
            display: none !important
        }
    </style>
    @endsection

    <section class="mt-32 px-4">
        <p class="text-lg font-medium mb-4">{{$faq->question}}</p>
        <textarea id="answer" name="answer">
            {!! $faq->answer !!}
        </textarea>
    </section>

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

</x-landing-page.layout>