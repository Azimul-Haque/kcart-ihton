@extends('layouts.app')
@section('title') ড্যাশবোর্ড | পরীক্ষাসমূহ @endsection

@section('content')

@section('page-header') ক্যাটাগরিভিত্তিক পরীক্ষাসমূহ @endsection

<div class="container-fluid">
    <div class="row">
        @php
            $colors = ['bg-info', 'bg-success', 'bg-warning', 'bg-danger', 'bg-primary', 'bg-teal', 'bg-purple'];
            $emojis = [
                            '📚', '🔬', '📖', '📊', '🎓', '📝', '🏫', '🧪', '🩺', '🏭', '🔧', '💰', '📇', 
                            '💻', '💡', '🎨', '📑', '🎯', '🏆', '⚖️', '📌', 
                            '⚡', '🧠', '📐', '📂', '📜', '🖊️', '🔍', '🌐', 
                            '🚀', '⭐', '📢', '🗓️', '📋', '🏅', '🥇', '📈'
                        ];
        @endphp

        @foreach($examcategories as $index => $category)
            @php
                $currentColor = $colors[$index % count($colors)];
            @endphp

            <div class="col-lg-3 col-6">
                <div class="small-box {{ $currentColor }} shadow border-0 position-relative" style="border-radius: 12px; overflow: hidden; transition: transform .3s ease;">
                    
                    {{-- ১. বুলেটপ্রুফ অন-ক্লিক এডিট বাটন --}}
                    <div class="position-absolute" style="top: 8px; right: 8px; z-index: 10;">
                        <button type="button" 
                                class="btn btn-sm btn-light shadow-sm rounded-circle" 
                                onclick='openEditModal({{ $category->id }}, @json($category->name), @json($category->thumbnail ?? "📚"), @json(route("dashboard.exams.category.update", ["id" => $category->id])))'
                                title="ক্যাটাগরি এডিট করুন"
                                style="width: 28px; height: 28px; padding: 0; line-height: 28px; opacity: 0.95;">
                            <i class="fas fa-pencil-alt text-dark" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>

                    {{-- অরিজিনাল ক্লিন ইনফো বডি --}}
                    <div class="inner p-3">
                        <h3 class="mb-0"><span class="mr-1">{{ $category->thumbnail ?? '📚' }}</span></h3>
                        <p class="font-weight-bold mb-0 text-truncate" style="font-size: 1.5rem;" title="{{ $category->name }}">
                            {{ $category->name }} <small>({{ bangla($category->exams->count()) }} টি পরীক্ষা)</small>
                        </p>
                    </div>
                    
                    {{-- ওয়াটারমার্ক আইকন সেকশন --}}
                    {{-- <div class="icon" style="top: 5px; right: 35px;">
                        <span style="font-size: 42px; opacity: 0.3;">{{ $category->thumbnail ?? '📚' }}</span>
                    </div> --}}
                    
                    <a href="{{ route('dashboard.exams', ['id' => $category->id]) }}" class="small-box-footer py-1" style="background: rgba(0,0,0,0.1); border-top: 1px solid rgba(255,255,255,0.1);">
                        পরীক্ষাসমূহ দেখুন <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
        @endforeach

        {{-- নতুন ক্যাটাগরি যোগ করার কার্ড --}}
        <div class="col-lg-3 col-6">
            <div class="small-box shadow-sm border-dashed" 
                 style="background-color: #f8f9fa; border: 2px dashed #ddd; border-radius: 12px; cursor: pointer; transition: transform .2s;"
                 data-toggle="modal" data-target="#addTopicModal">
                <div class="inner text-center" style="padding: 20px 10px;">
                    <i class="fas fa-plus-circle fa-2x text-warning mb-2"></i>
                    <p class="font-weight-bold text-muted mb-0">নতুন ক্যাটাগরি যোগ করুন</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ১. নতুন ক্যাটাগরি যোগ করার মডাল --}}
<div class="modal fade" id="addTopicModal" tabindex="-1" role="dialog" aria-labelledby="addTopicModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
        <div class="modal-header bg-warning">
          <h5 class="modal-title font-weight-bold" id="addTopicModalLabel"><i class="fas fa-folder-plus mr-2"></i>নতুন ক্যাটাগরি যোগ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="{{ route('dashboard.exams.category.store') }}">
            @csrf
            <div class="modal-body p-3">
                  <div class="form-group mb-3">
                      <label class="font-weight-bold text-secondary">ক্যাটাগরির নাম</label>
                      <div class="input-group">
                          <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="যেমন: বিসিএস প্রিলিমিনারি" required>
                          <div class="input-group-append">
                              <span class="input-group-text"><i class="far fa-bookmark"></i></span>
                          </div>
                      </div>
                  </div>

                  <div class="form-group mb-2">
                      <label class="font-weight-bold text-secondary">থাম্বনেইল (ইউনিকোড ইমোজি)</label>
                      <div class="input-group">
                          <input type="text" name="thumbnail" id="add_thumbnail_input" class="form-control text-center font-weight-bold" value="{{ old('thumbnail', '📚') }}" required style="font-size: 1.3rem;">
                          <div class="input-group-append">
                              <span class="input-group-text"><i class="far fa-image"></i></span>
                          </div>
                      </div>
                  </div>

                  <label class="small text-muted font-weight-bold mt-2">ইমোজিতে ক্লিক করে সিলেক্ট করুন:</label>
                  <div class="emoji-picker-grid p-2 border rounded bg-light d-flex flex-wrap justify-content-center" style="gap: 6px; max-height: 110px; overflow-y: auto;">
                      @foreach($emojis as $emoji)
                          <span class="emoji-option p-1 rounded cursor-pointer" onclick="selectEmoji('add_thumbnail_input', '{{ $emoji }}')" style="font-size: 1.4rem; cursor: pointer;">{{ $emoji }}</span>
                      @endforeach
                  </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
              <button type="submit" class="btn btn-warning">দাখিল করুন</button>
            </div>
        </form>
      </div>
    </div>
</div>

{{-- ২. ক্যাটাগরি এডিট করার মডাল --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 14px;">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title font-weight-bold" id="editCategoryModalLabel"><i class="fas fa-edit mr-2"></i>ক্যাটাগরি সংশোধন করুন</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="editCategoryForm" action="">
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="edit_category_id">

            <div class="modal-body p-3">
                  <div class="form-group mb-3">
                      <label class="font-weight-bold text-secondary">ক্যাটাগরির নাম</label>
                      <div class="input-group">
                          <input type="text" name="name" id="edit_category_name" class="form-control" placeholder="ক্যাটাগরির নাম" required>
                          <div class="input-group-append">
                              <span class="input-group-text"><i class="far fa-bookmark"></i></span>
                          </div>
                      </div>
                  </div>

                  <div class="form-group mb-2">
                      <label class="font-weight-bold text-secondary">থাম্বনেইল (ইউনিকোড ইমোজি)</label>
                      <div class="input-group">
                          <input type="text" name="thumbnail" id="edit_thumbnail_input" class="form-control text-center font-weight-bold" required style="font-size: 1.3rem;">
                          <div class="input-group-append">
                              <span class="input-group-text"><i class="far fa-image"></i></span>
                          </div>
                      </div>
                  </div>

                  <label class="small text-muted font-weight-bold mt-2">ইমোজিতে ক্লিক করে সিলেক্ট করুন:</label>
                  <div class="emoji-picker-grid p-2 border rounded bg-light d-flex flex-wrap justify-content-center" style="gap: 6px; max-height: 110px; overflow-y: auto;">
                      @foreach($emojis as $emoji)
                          <span class="emoji-option p-1 rounded cursor-pointer" onclick="selectEmoji('edit_thumbnail_input', '{{ $emoji }}')" style="font-size: 1.4rem; cursor: pointer;">{{ $emoji }}</span>
                      @endforeach
                  </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
              <button type="submit" class="btn btn-info">হালনাগাদ করুন</button>
            </div>
        </form>
      </div>
    </div>
</div>

@endsection

@section('third_party_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function selectEmoji(inputId, emoji) {
        document.getElementById(inputId).value = emoji;
    }

    // সরাসরি ডাটা পপুলেট করে মডাল শো করার ডাইরেক্ট মেথড
    function openEditModal(id, name, thumbnail, updateUrl) {
        document.getElementById('edit_category_id').value = id;
        document.getElementById('edit_category_name').value = name;
        document.getElementById('edit_thumbnail_input').value = thumbnail;
        document.getElementById('editCategoryForm').action = updateUrl;

        $('#editCategoryModal').modal('show');
    }

    // সার্চ ফিল্টার
    $(document).on('click', '#search-button', function() {
      if($('#search-param').val() != '') {
        var urltocall = '{{ route('dashboard.exams') }}' +  '/' + $('#search-param').val().replace(/\\|\//g, '%');
        location.href= urltocall;
      } else {
        $('#search-param').css({ "border": '#FF0000 2px solid'});
        Swal.fire({
            icon: 'warning',
            title: 'কিছু লিখে খুঁজুন!'
        })
      }
    });

    $("#search-param").keyup(function(e) {
      if(e.which == 13) {
        $("#search-button").click();
      }
    });
</script>

<style>
    .small-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .emoji-option:hover {
        background-color: #e2e8f0;
        transform: scale(1.2);
    }
    .border-dashed {
        border-style: dashed !important;
    }
</style>
@endsection