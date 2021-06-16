<div class="row clearfix">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
        <h3>Attachment Details</h3>
    </div>
</div>

<div class="row clearfix">
    <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
        <input type="text" name="title_attach" id="title" class="form-control" placeholder="Title" />
    </div>
    <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
        <input type="text" name="metaData" id="metaData" class="form-control" placeholder="Meta Data" />
    </div>
    <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
        <input type="text" name="vimeoVideo" id="vimeoVideo" class="form-control" placeholder="Vimeo Video ID" />
    </div>
    <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
        <input type="text" name="youtubeVideo" id="youtubeVideo" class="form-control" placeholder="Youtube Video ID" />
    </div>
    <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
        <input type="text" name="position" id="position" class="form-control" placeholder="Position" />
    </div>
</div>
<div class="row clearfix">
    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-group">
        <label>Upload a Video
            <input type="file" name="video_file" id="video_file" class="form-control" accept="video/avi,video/mpeg,video/quicktime,video/mp4"/>
            <small class="text-muted">*Video must not more than 5 MB. Only accept MP4, MPEG, AVI type.</small>
        </label>
    </div>
    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-group">
        <label>Upload a Photo File
            <input type="file" name="attachment_file[]" id="attachment_file" class="form-control" accept="image/jpeg" multiple/>
            <small class="text-muted">*Photos must be more than 500 px. on both horizontal and vertical dimensions. Only JPEG photo type.</small>
        </label>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
        <textarea class="form-control" name="description_attach" id="description" placeholder="Description" rows="3"></textarea>
    </div>
</div>