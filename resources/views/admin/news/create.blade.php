<x-admin.layout>

    @section('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.css">
    @endsection

    <div class="shadow-lg card">
        <div class="card-body">
            <form id="newsForm">
                @csrf
                <div class="form-group">
                    <label for="title">Judul</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="">Upload Gambar</label>
                    <div class="custom-file">
                        <input type="file" name="image" id="image" class="custom-file-input" required>
                        <label class="custom-file-label" for="image">Choose File</label>
                    </div>
                    <small class="form-text text-muted">Jenis file: jpg, jpeg, png.</small>
                </div>
                <div class="form-group">
                    <label for="description">Content</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <div class="mt-4 text-right">
                    <button type="button" class="btn btn-secondary" id="btnCancel">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    {{-- Include CKEditor --}}
    @section('script')
    {{-- <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

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
            style: {
                definitions: [
                    {
                        name: 'Bullet List',
                        element: 'ul',
                        attributes: { class: 'list-disc pl-6' } // Tailwind class untuk bullet list
                    },
                    {
                        name: 'Numbered List',
                        element: 'ol',
                        attributes: { class: 'list-decimal pl-6' } // Tailwind class untuk numbered list
                    },
                    {
                        name: 'Link',
                        element: 'a',
                        attributes: { class: 'underline text-blue-500' } // Tailwind class untuk link
                    },
                    {
                        name: 'Paragraph',
                        element: 'p',
                        attributes: { class: 'mt-4 mb-4' } // Tailwind spacing
                    }
                ]
            },
        };

        ClassicEditor.create(document.querySelector('#description'), editorConfig);
    </script>

    <script>
        $(document).ready(function() {
            $('#newsForm').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan menyimpan perubahan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('news.store') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // console.log('Response success:', response);
                                Swal.fire(
                                    'Berhasil!',
                                    'Berita berhasil ditambah.',
                                    'success'
                                ).then(() => {
                                    window.location.href = "{{ route('news.berita') }}";
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    // console.log('Response error:', response);
                                    'Gagal!',
                                    'Ada kesalahan saat menambah berita.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('#btnCancel').click(function() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan menyimpan perubahan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, batalkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if ($('#newsForm').length) {
                            $('#newsForm')[0].reset();
                        }
                        window.location.href = "{{ route('news.berita') }}";
                    }
                });
            });

            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
        });
    </script>
    @endsection
</x-admin.layout>
