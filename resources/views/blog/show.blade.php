@extends('apps.blog')

@section('content')
    @if (session()->has('success'))
        <div class="mb-2 alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @isset($user)
        <div class="my-2">
            <h1 class="text-center mb-1 bg-dark text-white py-2 rounded">{{ $user }}'s Blogs</h1>
            <hr>
        </div>
    @endisset
    <div class="my-2">
        <div class="row bg-info">
            <h2 class="display-5 p-2 fw-bold ff-catamaran col-lg-8">
                {{ $blog->title }}
            </h2>
            <div class="col-lg-4 d-flex flex-column">
                <ul class="list-unstyled my-auto float-end">
                    @foreach ($blog->contents->where('type', 'header') as $item)
                        <li class="ps-5"># <a href="#{{ Str::slug($item->content, '_') }}"
                                class="text-dark">{{ $item->content }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="my-1 p-1 d-flex justify-content-between">
            <a href="{{ route('blog', ['user', $blog->user_id]) }}" class="btn btn-link text-decoration-none"><i
                    class="far fa-user-circle align-middle"></i>
                {{ $blog->user->name }}
            </a>
            @if ($blog->user->id === Auth::id())
                <button class="btn btn-sm btn-danger mx-1" type="button" data-bs-toggle="modal"
                    data-bs-target="#deleteBlogModal"><i class="fas fa-trash-alt"></i> Blog</button>
                <!-- Modal -->
                <div class="modal fade" id="deleteBlogModal" tabindex="-1" aria-labelledby="deleteBlogModal"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header alert alert-danger">
                                <h5 class="modal-title">Confirm delete?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex justify-content-around">
                                <div class="mb-1">
                                    <p>This action can not be reversed.</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('blog.destroy', $blog->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            @foreach ($blog->contents as $content)
                <div class="col-12 my-2 position-relative">
                    @switch($content->type)
                        @case('header')
                            @if ($blog->user->id === Auth::id())
                                <div class="position-absolute top-0 end-0">
                                    <button class="btn btn-sm btn-link badge bg-info" type="button" data-bs-toggle="modal"
                                        data-bs-target="#headerEditModal_{{ $content->id }}"><i
                                            class="far fa-edit align-middle"></i> Edit</button>
                                    @include('blog.includes.edit-content', ['type' => 'header', 'id' => $content->id, 'content'
                                    => $content->content])
                                </div>
                            @endif
                            <div class="bg-light p-2 fs-5 overflow-auto mt-3 rounded ff-catamaran"
                                id="{{ Str::slug($content->content, '_') }}">
                                <h3> <i class="fas fa-link text-info"></i>
                                    {{ $content->content }}</h3>
                            </div>
                        @break
                        @case('text')
                            @if ($blog->user->id === Auth::id())
                                <div class="position-absolute top-0 end-0">
                                    <button class="btn btn-sm btn-link badge bg-info" type="button" data-bs-toggle="modal"
                                        data-bs-target="#textEditModal_{{ $content->id }}"><i
                                            class="far fa-edit align-middle"></i> Edit</button>
                                    @include('blog.includes.edit-content', ['type' => 'text', 'id' => $content->id, 'content' =>
                                    $content->content])
                                </div>
                            @endif
                            <div class="bg-light p-2 fs-5 overflow-auto mt-3 rounded ff-merryweather">
                                {!! $content->content !!}
                            </div>
                        @break
                        @case('image')
                            @if ($blog->user->id === Auth::id())
                                <div class="position-absolute top-0 end-0">
                                    <button class="btn btn-sm btn-link badge bg-info" type="button" data-bs-toggle="modal"
                                        data-bs-target="#imageEditModal_{{ $content->id }}"><i
                                            class="far fa-edit align-middle"></i> Edit</button>
                                    @include('blog.includes.edit-content', ['type' => 'image', 'id' => $content->id, 'content'
                                    => $content->content])
                                </div>
                            @endif
                            <img src="{{ $content->content }}" alt="Not a valid image" class="mt-3" style="max-width: 100%">
                        @break
                        @case('code')
                            @if ($blog->user->id === Auth::id())
                                <div class="position-absolute top-0 end-0">
                                    <button class="btn btn-sm btn-link badge bg-info" type="button" data-bs-toggle="modal"
                                        data-bs-target="#codeEditModal_{{ $content->id }}"><i
                                            class="far fa-edit align-middle"></i> Edit</button>
                                    @include('blog.includes.edit-content', ['type' => 'code', 'id' => $content->id, 'content' =>
                                    $content->content])
                                </div>
                            @endif
                            <div class="bg-dark text-warning p-2 overflow-auto text-nowrap mt-3 ff-source-code">
                                {!! $content->content !!}
                            </div>
                        @break
                        @default

                    @endswitch
                </div>
            @endforeach
        </div>
        <div class="d-flex mt-5">
            <hr class="flex-grow-1">
            <div id="modals">
                @if ($blog->user->id === Auth::id())
                    <button class="btn btn-primary btn-add" type="button" data-bs-toggle="modal"
                        data-bs-target="#contentModal">
                        <i class="fas fa-plus-circle text-light"></i>
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="contentModal" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="contentModal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="contentModal">Choose the content</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body d-flex justify-content-around flex-wrap mb-3">

                                    <button class="btn btn-sm btn-primary mb-2" type="button" data-bs-toggle="modal"
                                        data-bs-target="#headerInputModal"> <i class="fas fa-link"></i> Header</button>
                                    @include('blog.includes.add-content', ['type' => 'header'])

                                    <button class="btn btn-sm btn-primary mb-2" type="button" data-bs-toggle="modal"
                                        data-bs-target="#textInputModal"> <i class="fa fa-keyboard"></i> Text</button>
                                    @include('blog.includes.add-content', ['type' => 'text'])

                                    <button class="btn btn-sm btn-primary mb-2" type="button" data-bs-toggle="modal"
                                        data-bs-target="#imageInputModal"> <i class="fa fa-image"></i> Image</button>
                                    @include('blog.includes.add-content', ['type' => 'image'])

                                    <button class="btn btn-sm btn-primary mb-2" type="button" data-bs-toggle="modal"
                                        data-bs-target="#codeInputModal"> <i class="fa fa-code"></i> Code</button>
                                    @include('blog.includes.add-content', ['type' => 'code'])

                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="alert alert-secondary">End of the blog</p>
                @endif
            </div>
            <hr class="flex-grow-1">
        </div>
    </div>
@endsection
