<style>
    .widget {
        margin-bottom: 30px;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        word-wrap: break-word; /* টেক্সট যাতে স্ক্রিনের বাইরে না যায় */
    }
    .widget-title {
        font-size: 18px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        color: #333;
        font-weight: 700;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 8px;
    }
    .search-input {
        width: 100%;
        padding: 10px 45px 10px 15px; /* বাটনের জন্য ডানে জায়গা রাখা হয়েছে */
        border: 1px solid #ddd;
        border-radius: 25px;
        outline: none;
        transition: 0.3s;
        font-size: 14px;
    }
    .search-input:focus {
        border-color: #0062cc;
        box-shadow: 0 0 5px rgba(0,98,204,0.2);
    }
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .category-list li {
        padding: 10px 0;
        border-bottom: 1px solid #f9f9f9;
    }
    .category-list li:last-child {
        border-bottom: none;
    }
    .category-list li a {
        color: #555;
        text-decoration: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: 0.3s;
        font-size: 15px;
    }
    .category-list li a:hover {
        color: #0062cc;
        padding-left: 5px;
    }
    .category-count {
        background: #f0f7ff;
        color: #0062cc;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .popular-post-item {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
        align-items: center;
        align-items: flex-start;
    }
    .popular-post-thumb {
        width: 110px;
        height: 65px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0; /* ইমেজ যাতে ছোট হয়ে না যায় */
    }
    .popular-post-title {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        color: #333;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: 0.2s;
    }
    .popular-post-title:hover {
        color: #0062cc;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .widget {
            margin-bottom: 25px;
            padding: 15px;
        }
    }
    
    @media (max-width: 575px) {
        .popular-post-thumb {
            width: 90px;
            height: 55px;
            flex-shrink: 0;
        }
        .widget-title {
            font-size: 16px;
        }
        .category-list li a {
            font-size: 14px;
        }
    }
</style>

<div class="widget">
    <h5 class="widget-title">সার্চ</h5>
    <form action="#!" method="GET">
        <div style="position: relative;">
            <input type="text" placeholder="ব্লগ খুঁজুন..." class="search-input" name="search">
            <button type="submit" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #777; padding: 0 10px;">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>
</div>

<div class="widget">
    <h5 class="widget-title">ব্লগ ক্যাটাগরি</h5>
    <div class="widget-body">
        <ul class="category-list">
            @foreach($categories as $category)
            <li>
                <a href="{{ route('blog.categorywise', str_replace(' ', '-', $category->name)) }}">
                    <span>{{ $category->name }}</span>
                    <span class="category-count">{{ bangla($category->blogs->count()) }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="widget">
    <h5 class="widget-title">জনপ্রিয় ব্লগ</h5>
    <div class="widget-body">
        @foreach($populars as $popular)
        <div class="popular-post-item">
            <a href="{{ route('blog.single', $popular->slug) }}" style="flex-shrink: 0;"> {{-- এখানে ফ্লেক্স-শ্রিঙ্ক জিরো রাখা জরুরি --}}
                @if($popular->featured_image != null && file_exists(public_path('images/blogs/' . $popular->featured_image)))
                    <img src="{{ asset('images/blogs/'.$popular->featured_image) }}" class="popular-post-thumb" alt="{{ $popular->title }}"/>
                @else
                    <img src="{{ asset('images/favicon.png') }}" class="popular-post-thumb" alt="Default"/>
                @endif
            </a>
            <div class="ms-1"> {{-- একটু মার্জিন যোগ করা হয়েছে --}}
                <a href="{{ route('blog.single', $popular->slug) }}" class="popular-post-title" style="display: block; line-height: 1.4;">
                    {{ $popular->title }}
                </a>
                <small class="text-muted" style="font-size: 11px; display: block; margin-top: 5px;">
                    <i class="far fa-calendar-alt"></i> {{ bangla(date('M d, Y', strtotime($popular->created_at))) }}
                </small>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="widget">
    <h5 class="widget-title">আর্কাইভ</h5>
    <div class="widget-body">
        <ul class="category-list">
            @foreach($archives as $archive)
            <li>
                <a href="{{ route('blog.monthwise', date('Y-m', strtotime($archive->created_at))) }}">
                    <span>{{ bangla(date('F Y', strtotime($archive->created_at))) }}</span>
                    <span class="category-count">{{ bangla($archive->total) }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>