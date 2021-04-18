<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Traits\FileUploadTrait;
use \App\Models\ChatterCategory;
use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of Taxes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 3);
        })->get()->pluck('name', 'id');

        $galleries = Gallery::all();
        return view('backend.gallery.index', compact('students', 'galleries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $forum_categories = ChatterCategory::orderBy('created_at', 'desc')
            ->get()->pluck('name', 'id')->prepend('Please select', '');
        return view('backend.forum-categories.create', compact('forum_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Log::info($request->all());
        $gallery = new Gallery();
        $gallery->user_id = $request->student_id;
        $gallery->title = $request->course_name;
        $gallery->save();

        if($request->hasFile('gallery_file')){
            $request = $this->saveAllFiles($request, 'gallery_file', Gallery::class, $gallery, true);
        }

        return redirect()->route('admin.gallery.index')->withFlashSuccess('Successfully uploaded gallery.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();

        return back()->withFlashSuccess(__('alerts.backend.general.deleted'));
    }

    public function deleteMedia($media)
    {
        if($media){
            //Delete Related data
            $filename = $media->file_name;

            $media->delete();

            //Delete Photo
            $destinationPath = public_path() . '/storage/uploads/'.$filename;
            if (file_exists($destinationPath)) {
                unlink($destinationPath);
            }
        }
    }

}
