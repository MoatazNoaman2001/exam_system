<div>
    <!-- Life is available only in the present moment. - Thich Nhat Hanh -->
</div>
@props(['exam'])
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="title_en">{{ __('Exam Title (English)') }}</label>
            <input type="text" class="form-control @error('title_en') is-invalid @enderror" 
                   id="title_en" name="title_en" required>
            @error('title_en')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="title_ar">{{ __('Exam Title (Arabic)') }} <span class="text-muted">(العنوان بالعربية)</span></label>
            <input type="text" class="form-control @error('title_ar') is-invalid @enderror" 
                   id="title_ar" name="title_ar" required dir="rtl">
            @error('title_ar')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="description_en">{{ __('Description (English)') }}</label>
            <textarea class="form-control" id="description_en" name="description_en" rows="3"></textarea>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="description_ar">{{ __('Description (Arabic)') }} <span class="text-muted">(الوصف بالعربية)</span></label>
            <textarea class="form-control" id="description_ar" name="description_ar" rows="3" dir="rtl"></textarea>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="duration">{{ __('Duration (minutes)') }}</label>
    <input type="number" class="form-control @error('duration') is-invalid @enderror" 
           id="duration" name="duration" min="1" required>
    @error('duration')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>