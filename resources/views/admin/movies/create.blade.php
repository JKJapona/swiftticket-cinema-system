<div class="modal fade" id="createMovieModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            
            {{-- Header --}}
            <div class="modal-header bg-slate-50 border-0 px-4 py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-swift-blue text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-film fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Add New Movie</h2>
                        <p class="text-slate-500 caption mb-0 fw-600">Expanding the Digital Library</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data" id="createMovieForm">
                @csrf
                <div class="modal-body p-3 bg-white">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 py-2 mb-3 rounded-3">
                            <ul class="mb-0 small fw-600">
                                @foreach ($errors->all() as $error)
                                    <li><i class="bi bi-exclamation-circle-fill me-2"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row g-3">
                        {{-- Left Column --}}
                        <div class="col-lg-7">
                            <div class="row gx-2 gy-2">
                                <div class="col-12">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Movie Title</label>
                                    <input type="text" name="title" class="form-control form-control-sm border-2" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Genre</label>
                                    <input type="text" name="genre" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-4">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Runtime</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="runtime_minutes" class="form-control">
                                        <span class="input-group-text bg-slate-100 text-slate-500" style="font-size: 10px;">mins</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Age Rating</label>
                                    <select name="rating" class="form-select form-select-sm border-2">
                                        <option value="TBA">TBA</option>
                                        <option value="G">G</option>
                                        <option value="PG">PG</option>
                                        <option value="R-13">R-13</option>
                                        <option value="R-16">R-16</option>
                                        <option value="R-18">R-18</option>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Release Date</label>
                                    <input type="date" name="release_date" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-7">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Cast Members</label>
                                    <input type="text" name="cast_members" class="form-control form-control-sm">
                                </div>

                                <div class="col-12">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Synopsis</label>
                                    <textarea name="synopsis" class="form-control form-control-sm" rows="2" style="font-size: 13px;"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="col-lg-5">
                            <div class="bg-slate-50 rounded-3 p-2 border h-100 shadow-sm">
                                <h4 class="caption text-slate-900 mb-2 d-flex align-items-center">
                                    <i class="bi bi-images me-2 text-swift-blue"></i> Media Assets
                                </h4>

                                {{-- Poster --}}
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="label text-slate-500 text-uppercase fw-700 mb-0">Poster (Portrait)</label>
                                        <div class="form-check form-switch m-0" style="min-height: auto;">
                                            <input class="form-check-input" type="checkbox" id="posterType" onchange="toggleInput('poster')" style="height: 15px; width: 30px;">
                                            <label class="caption text-slate-500" for="posterType" style="font-size: 9px !important;">URL</label>
                                        </div>
                                    </div>
                                    <div id="poster_file_group" class="media-dropzone text-center p-2 border-2 border-dashed rounded-3 bg-white">
                                        <input type="file" name="poster_file" class="d-none" id="posterFile" onchange="previewMedia(this, 'poster_preview')">
                                        <label for="posterFile" class="cursor-pointer mb-0 d-block">
                                            <img id="poster_preview" src="" class="img-fluid rounded mb-1 d-none mx-auto" style="max-height: 100px;">
                                            <div id="poster_prompt">
                                                <i class="bi bi-plus-circle fs-6 text-slate-300"></i>
                                                <p class="caption text-slate-500 mb-0" style="font-size: 9px !important;">Upload</p>
                                            </div>
                                        </label>
                                    </div>
                                    <input type="url" name="poster_url" id="poster_url_input" class="form-control form-control-sm d-none mt-1" placeholder="https://...">
                                </div>

                                {{-- Cover --}}
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="label text-slate-500 text-uppercase fw-700 mb-0">Cover (Landscape)</label>
                                        <div class="form-check form-switch m-0" style="min-height: auto;">
                                            <input class="form-check-input" type="checkbox" id="coverType" onchange="toggleInput('cover')" style="height: 15px; width: 30px;">
                                            <label class="caption text-slate-500" for="coverType" style="font-size: 9px !important;">URL</label>
                                        </div>
                                    </div>
                                    <div id="cover_file_group" class="media-dropzone text-center p-2 border-2 border-dashed rounded-3 bg-white">
                                        <input type="file" name="cover_file" class="d-none" id="coverFile" onchange="previewMedia(this, 'cover_preview')">
                                        <label for="coverFile" class="cursor-pointer mb-0 d-block">
                                            <img id="cover_preview" src="" class="img-fluid rounded mb-1 d-none mx-auto" style="max-height: 80px; width: 100%; object-fit: cover;">
                                            <div id="cover_prompt">
                                                <i class="bi bi-card-image fs-6 text-slate-300"></i>
                                                <p class="caption text-slate-500 mb-0" style="font-size: 9px !important;">Upload</p>
                                            </div>
                                        </label>
                                    </div>
                                    <input type="url" name="cover_url" id="cover_url_input" class="form-control form-control-sm d-none mt-1" placeholder="https://...">
                                </div>

                                {{-- Trailer URL --}}
                                <div class="mb-0">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1 d-block">Trailer (YouTube)</label>
                                    <div class="input-group input-group-sm shadow-sm">
                                        <span class="input-group-text bg-white border-end-0 text-slate-400">
                                            <i class="bi bi-play-btn-fill"></i>
                                        </span>
                                        <input type="url" name="trailer_url" class="form-control border-start-0 ps-0" placeholder="https://...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-slate-50 border-0 p-2">
                    <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-600 btn-sm me-3" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm">
                        Publish Movie
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleInput(prefix) {
        const isUrl = document.getElementById(prefix + 'Type').checked;
        const fileGroup = document.getElementById(prefix + '_file_group');
        const urlInput = document.getElementById(prefix + '_url_input');
        if (isUrl) {
            fileGroup.classList.add('d-none');
            urlInput.classList.remove('d-none');
            urlInput.setAttribute('required', 'required');
        } else {
            fileGroup.classList.remove('d-none');
            urlInput.classList.add('d-none');
            urlInput.removeAttribute('required');
        }
    }

    function previewMedia(input, previewId) {
        const preview = document.getElementById(previewId);
        const prompt = document.getElementById(previewId.replace('preview', 'prompt'));
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                prompt.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>