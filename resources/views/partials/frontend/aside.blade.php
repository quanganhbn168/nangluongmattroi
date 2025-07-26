<!-- SIDEBAR -->
            <aside class="col-md-3 d-none d-md-block">
                <div class="form box-shadow bg-white p-3 mb-4" id="contact-form">
                    <form action="{{ route('frontend.post.search') }}" method="GET" class="sidebar-search-form">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm bài viết...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <nav class="sidebar-navigation bg-white box-shadow mb-4">
                    <h5 class="sidebar-title">
                        <span>
                            Danh mục dịch vụ
                        </span>
                    </h5>
                    <ul class="sidebar-menu">
                        @foreach($serviceCategory as $cat)
                            <li>
                                <a href="{{ route('slug.resolve', $cat->slug) }}">{{ $cat->name }}</a>
                                @if($cat->children->count())
                                    <ul class="submenu">
                                        @foreach($cat->children as $child)
                                            <li>
                                                <a href="{{ route('slug.resolve', $child->slug) }}">{{ $child->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
                <nav class="sidebar-navigation bg-white box-shadow mb-4">
                    <h5 class="sidebar-title">
                        <span>Tin tức mới nhất</span>
                    </h5>

                    <ul class="sidebar-menu">
                        @foreach($posts as $post)
                            <li class="sidebar-post">
                                <a href="{{ route('slug.resolve', $post->slug) }}" class="sidebar-post-link">
                                    <img
                                        src="{{ asset($post->image) }}"
                                        alt="{{ $post->title }}"
                                        class="sidebar-post-thumb"
                                        width="80" height="60"
                                        loading="lazy">
                                    <span class="sidebar-post-title">{{ $post->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
                <nav class="sidebar-navigation bg-white box-shadow">
                    <h5 class="sidebar-title">
                        <span>Nhận ưu đãi ngay</span>
                    </h5>
                    <div class="contact-form">
                        <form id="contact-form" action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-2">
                                <input type="text" name="name" class="form-control" placeholder="Họ và tên *" required>
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" name="phone" class="form-control" placeholder="Số điện thoại *" required>
                            </div>
                            <div class="form-group mb-2">
                                <textarea name="message" rows="3" class="form-control" placeholder="Nội dung liên hệ"></textarea>
                            </div>
                            <div class="form-group mb-0 text-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Gửi liên hệ</button>
                            </div>
                        </form>
                    </div>
                </nav>
                
            </aside>
            @push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script>
    // Custom rule kiểm tra số điện thoại Việt Nam
            $.validator.addMethod("phoneVN", function (value, element) {
                return this.optional(element) || /^(0[3|5|7|8|9])[0-9]{8}$|^\+84[3|5|7|8|9][0-9]{8}$/.test(value);
            }, "Số điện thoại không hợp lệ");

            $(document).ready(function () {
                $('#contact-form').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 2
                        },
                        phone: {
                            required: true,
                            phoneVN: true
                        },
                    },
                    messages: {
                        name: {
                            required: "Vui lòng nhập họ và tên",
                            minlength: "Tên quá ngắn"
                        },
                        phone: {
                            required: "Vui lòng nhập số điện thoại",
                            phoneVN: "Số điện thoại không hợp lệ (ví dụ: 098xxxxxxx)"
                        },
                        message: {
                            maxlength: "Ý kiến không vượt quá 1000 ký tự"
                        }
                    },
                    errorElement: 'small',
                    errorClass: 'text-danger',
                    highlight: function (element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });
        </script>
@endpush
