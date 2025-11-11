<x-admin.layout>
    @section('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.css">
    @endsection

    <div class="card shadow-lg">
        <div class="card-body">
            <form id="editAboutForm">
                <input type="hidden" name="id" value="{{$about->id}}">
                @csrf
                <div class="form-group">
                    <label for="About">About</label>
                    <textarea class="form-control" id="answer" name="answer">
                        {!! $about->content !!}
                    </textarea>
                </div>
                <div class="text-right mt-4">
                    <a href="{{route('landingpage.tentang')}}">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                    <button type="submit" class="btn btn-primary">Save</button>
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
            Essentials,
            FindAndReplace,
            GeneralHtmlSupport,
            Heading,
            Highlight,
            HorizontalLine,
            HtmlEmbed,
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
            Undo,
            SourceEditing,  // Import SourceEditing plugin
            Markdown
        } from 'ckeditor5';

        const editorConfig = {
            plugins: [
                SourceEditing,
                HtmlEmbed,
                AccessibilityHelp,
                Alignment,
                Autoformat,
                AutoLink,
                Autosave,
                BalloonToolbar,
                BlockQuote,
                Bold,
                CloudServices,
                Essentials,
                FindAndReplace,
                GeneralHtmlSupport,
                Heading,
                Highlight,
                HorizontalLine,
                HtmlEmbed,
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
                Undo,
            ],
            toolbar: {
                items: [
                    'sourceEditing', // Place sourceEditing at the beginning
                    'heading',
                    'style',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'link',
                    'insertTable',
                    'highlight',
                    'blockQuote',
                    '|',
                    'alignment',
                    '|',
                    'bulletedList',
                    'numberedList',
                    'todoList',
                    'indent',
                    'outdent'
                ],
                shouldNotGroupWhenFull: false
            },
            balloonToolbar: ['bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList'],
            heading: {
                options: [
                    {
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph'
                    },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            htmlSupport: {
                allow: [
                    {
                        name: /^.*$/,
                        styles: true,
                        attributes: true,
                        classes: true
                    }
                ]
            },
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
                decorators: {
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            menuBar: {
                isVisible: true
            },
            placeholder: 'Type or paste your content here!',
            style: {
                definitions: [
                    {
                        name: 'Article category',
                        element: 'h3',
                        classes: ['category']
                    },
                    {
                        name: 'Title',
                        element: 'h2',
                        classes: ['document-title']
                    },
                    {
                        name: 'Subtitle',
                        element: 'h3',
                        classes: ['document-subtitle']
                    },
                    {
                        name: 'Info box',
                        element: 'p',
                        classes: ['info-box']
                    },
                    {
                        name: 'Side quote',
                        element: 'blockquote',
                        classes: ['side-quote']
                    },
                    {
                        name: 'Marker',
                        element: 'span',
                        classes: ['marker']
                    },
                    {
                        name: 'Spoiler',
                        element: 'span',
                        classes: ['spoiler']
                    },
                    {
                        name: 'Code (dark)',
                        element: 'pre',
                        classes: ['fancy-code', 'fancy-code-dark']
                    },
                    {
                        name: 'Code (bright)',
                        element: 'pre',
                        classes: ['fancy-code', 'fancy-code-bright']
                    }
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
            },
        };

        ClassicEditor.create(document.querySelector('#answer'), editorConfig);
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#editAboutForm').submit(function(e) {
                e.preventDefault()

                var form = $(this)
                var data = form.serialize()

                console.log(data);

                $.ajax({
                    url: "{{route('landingpage.tentangupdate')}}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    data: data,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);

                        Swal.fire({
                            title: "Berhasil",
                            text: data.msg,
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            })
        })
    </script>

    @endsection

</x-admin.layout>
