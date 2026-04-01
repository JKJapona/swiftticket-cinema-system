<div class="modal fade" id="editMovieModal{{ $movie->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            
            {{-- Header --}}
            <div class="modal-header bg-slate-50 border-0 px-4 py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-warning text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Edit Movie</h2>
                        <p class="text-slate-500 caption mb-0 fw-600">Updating: {{ $movie->title }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data" id="editMovieForm{{ $movie->id }}">
                @csrf
                @method('PUT') 
                
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
                                    <input type="text" name="title" class="form-control form-control-sm border-2" value="{{ $movie->title }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Genre</label>
                                    <input type="text" name="genre" class="form-control form-control-sm" value="{{ $movie->genre }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Runtime</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="runtime_minutes" class="form-control" value="{{ $movie->runtime_minutes }}">
                                        <span class="input-group-text bg-slate-100 text-slate-500" style="font-size: 10px;">mins</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Age Rating</label>
                                    <select name="rating" class="form-select form-select-sm border-2">
                                        <option value="TBA" {{ $movie->rating == 'TBA' ? 'selected' : '' }}>TBA</option>
                                        <option value="G" {{ $movie->rating == 'G' ? 'selected' : '' }}>G</option>
                                        <option value="PG" {{ $movie->rating == 'PG' ? 'selected' : '' }}>PG</option>
                                        <option value="R-13" {{ $movie->rating == 'R-13' ? 'selected' : '' }}>R-13</option>
                                        <option value="R-16" {{ $movie->rating == 'R-16' ? 'selected' : '' }}>R-16</option>
                                        <option value="R-18" {{ $movie->rating == 'R-18' ? 'selected' : '' }}>R-18</option>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Release Date</label>
                                    <input type="date" name="release_date" class="form-control form-control-sm" value="{{ $movie->release_date ? $movie->release_date->format('Y-m-d') : '' }}">
                                </div>

                                <div class="col-md-7">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Cast Members</label>
                                    <input type="text" name="cast_members" class="form-control form-control-sm" value="{{ $movie->cast_members }}">
                                </div>

                                <div class="col-12">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1">Synopsis</label>
                                    <textarea name="synopsis" class="form-control form-control-sm" rows="2" style="font-size: 13px;">{{ $movie->synopsis }}</textarea>
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
                                            <input class="form-check-input" type="checkbox" id="posterTypeEdit{{ $movie->id }}" onchange="toggleEditInput('poster', {{ $movie->id }})" style="height: 15px; width: 30px;">
                                            <label class="caption text-slate-500" for="posterTypeEdit{{ $movie->id }}" style="font-size: 9px !important;">URL</label>
                                        </div>
                                    </div>
                                    <div id="poster_file_group_edit{{ $movie->id }}" class="media-dropzone text-center p-1 border-2 border-dashed rounded-3 bg-white">
                                        <input type="file" name="poster_file" class="d-none" id="posterFileEdit{{ $movie->id }}" onchange="previewEditMedia(this, 'poster_preview_edit{{ $movie->id }}')">
                                        <label for="posterFileEdit{{ $movie->id }}" class="cursor-pointer mb-0 d-block">
                                            <img id="poster_preview_edit{{ $movie->id }}" src="{{ $movie->poster_url }}" class="img-fluid rounded mb-1 mx-auto {{ $movie->poster_path || $movie->poster_url ? '' : 'd-none' }}" style="max-height: 100px;">
                                            <div id="poster_prompt_edit{{ $movie->id }}" class="{{ $movie->poster_path || $movie->poster_url ? 'd-none' : '' }}">
                                                <i class="bi bi-plus-circle fs-6 text-slate-300"></i>
                                                <p class="caption text-slate-500 mb-0" style="font-size: 9px !important;">Update</p>
                                            </div>
                                        </label>
                                    </div>
                                    <input type="url" name="poster_url" id="poster_url_input_edit{{ $movie->id }}" class="form-control form-control-sm d-none mt-1" placeholder="https://..." value="{{ Str::startsWith($movie->poster_path, 'http') ? $movie->poster_path : '' }}">
                                </div>

                                {{-- Cover --}}
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="label text-slate-500 text-uppercase fw-700 mb-0">Cover (Landscape)</label>
                                        <div class="form-check form-switch m-0" style="min-height: auto;">
                                            <input class="form-check-input" type="checkbox" id="coverTypeEdit{{ $movie->id }}" onchange="toggleEditInput('cover', {{ $movie->id }})" style="height: 15px; width: 30px;">
                                            <label class="caption text-slate-500" for="coverTypeEdit{{ $movie->id }}" style="font-size: 9px !important;">URL</label>
                                        </div>
                                    </div>
                                    <div id="cover_file_group_edit{{ $movie->id }}" class="media-dropzone text-center p-1 border-2 border-dashed rounded-3 bg-white">
                                        <input type="file" name="cover_file" class="d-none" id="coverFileEdit{{ $movie->id }}" onchange="previewEditMedia(this, 'cover_preview_edit{{ $movie->id }}')">
                                        <label for="coverFileEdit{{ $movie->id }}" class="cursor-pointer mb-0 d-block">
                                            <img id="cover_preview_edit{{ $movie->id }}" src="{{ $movie->cover_url }}" class="img-fluid rounded mb-1 mx-auto {{ $movie->cover_path || $movie->cover_url ? '' : 'd-none' }}" style="max-height: 80px; width: 100%; object-fit: cover;">
                                            <div id="cover_prompt_edit{{ $movie->id }}" class="{{ $movie->cover_path || $movie->cover_url ? 'd-none' : '' }}">
                                                <i class="bi bi-card-image fs-6 text-slate-300"></i>
                                                <p class="caption text-slate-500 mb-0" style="font-size: 9px !important;">Update</p>
                                            </div>
                                        </label>
                                    </div>
                                    <input type="url" name="cover_url" id="cover_url_input_edit{{ $movie->id }}" class="form-control form-control-sm d-none mt-1" placeholder="https://..." value="{{ Str::startsWith($movie->cover_path, 'http') ? $movie->cover_path : '' }}">
                                </div>

                                {{-- Trailer URL --}}
                                <div class="mb-0">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1 d-block">Trailer (YouTube)</label>
                                    <div class="input-group input-group-sm shadow-sm">
                                        <span class="input-group-text bg-white border-end-0 text-slate-400">
                                            <i class="bi bi-play-btn-fill"></i>
                                        </span>
                                        <input type="url" name="trailer_url" class="form-control border-start-0 ps-0" placeholder="https://..." value="{{ $movie->trailer_url }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-slate-50 border-0 p-2">
                    <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-600 btn-sm me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm">
                        Update Movie
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleEditInput(prefix, id) {
        const isUrl = document.getElementById(prefix + 'TypeEdit' + id).checked;
        const fileGroup = document.getElementById(prefix + '_file_group_edit' + id);
        const urlInput = document.getElementById(prefix + '_url_input_edit' + id);
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

    function previewEditMedia(input, previewId) {
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