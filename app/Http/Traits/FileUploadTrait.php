<?php

namespace App\Http\Traits;

use App\Models\Media;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

trait FileUploadTrait
{

    /**
     * File upload trait used in controllers to upload files
     */
    public function saveFiles(Request $request)
    {
        ini_set('memory_limit', '-1');
        if (!file_exists(public_path('storage/uploads'))) {
            mkdir(public_path('storage/uploads'), 0777);
            mkdir(public_path('storage/uploads/thumb'), 0777);
        }

        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                if ($request->has($key . '_max_width') && $request->has($key . '_max_height')) {
                    // Check file width
                    $extension = array_last(explode('.',$request->file($key)->getClientOriginalName()));
                    $name = array_first(explode('.',$request->file($key)->getClientOriginalName()));
                    $filename = time() . '-' . str_slug($name).'.'.$extension;
                    $file = $request->file($key);
                    $image = Image::make($file);
                    if (!file_exists(public_path('storage/uploads/thumb'))) {
                        mkdir(public_path('storage/uploads/thumb'), 0777, true);
                    }

                  Image::make($file)->resize(50, 50)->save(public_path('storage/uploads/thumb') . '/' . $filename);

                    $width = $image->width();
                    $height = $image->height();
                    if ($width > $request->{$key . '_max_width'} && $height > $request->{$key . '_max_height'}) {
                        $image->resize($request->{$key . '_max_width'}, $request->{$key . '_max_height'});
                    } elseif ($width > $request->{$key . '_max_width'}) {
                        $image->resize($request->{$key . '_max_width'}, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    } elseif ($height > $request->{$key . '_max_width'}) {
                        $image->resize(null, $request->{$key . '_max_height'}, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    $image->save(public_path('storage/uploads') . '/' . $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                } else {

                    $extension = array_last(explode('.',$request->file($key)->getClientOriginalName()));
                    $name = array_first(explode('.',$request->file($key)->getClientOriginalName()));
                    $filename = time() . '-' . str_slug($name).'.'.$extension;
                    $request->file($key)->move(public_path('storage/uploads'), $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                }
            }
        }
        return $finalRequest;
    }

    public function saveSingleFiles(Request $request, $downloadable_file_input = null, $model_type = null, $model = null, $withSize)
    {
        ini_set('memory_limit', '-1');
        if (!file_exists(public_path('storage/uploads'))) {
            mkdir(public_path('storage/uploads'), 0777);
            mkdir(public_path('storage/upload/thumb'), 0777);
        }
        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                if ($key == $downloadable_file_input) {
                    if($withSize){
                        if ($request->has($key . '_max_width') && $request->has($key . '_max_height')) {
                            // Check file width
                            $extension = array_last(explode('.',$request->file($key)->getClientOriginalName()));
                            $name = array_first(explode('.',$request->file($key)->getClientOriginalName()));
                            $filename = time() . '-' . str_slug($name).'.'.$extension;
                            $file = $request->file($key);
                            $size = $file->getSize() / 1024;
                            $image = Image::make($file);
                            if (!file_exists(public_path('storage/uploads/thumb'))) {
                                mkdir(public_path('storage/uploads/thumb'), 0777, true);
                            }

                            Image::make($file)->resize(50, 50)->save(public_path('storage/uploads/thumb') . '/' . $filename);

                            $width = $image->width();
                            $height = $image->height();
                            if ($width > $request->{$key . '_max_width'} && $height > $request->{$key . '_max_height'}) {
                                $image->resize($request->{$key . '_max_width'}, $request->{$key . '_max_height'});
                            } elseif ($width > $request->{$key . '_max_width'}) {
                                $image->resize($request->{$key . '_max_width'}, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                            } elseif ($height > $request->{$key . '_max_width'}) {
                                $image->resize(null, $request->{$key . '_max_height'}, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                            }
                            $image->save(public_path('storage/uploads') . '/' . $filename);
                            Media::create([
                                'model_type' => $model_type,
                                'model_id' => $model->id,
                                'name' => $filename,
                                'type' => $file->getClientMimeType(),
                                'file_name' => $filename,
                                'size' => $size,
                            ]);
                        }

                    }else {
                        $extension = array_last(explode('.',$request->file($key)->getClientOriginalName()));
                        $name = array_first(explode('.',$request->file($key)->getClientOriginalName()));
                        $filename = time() . '-' . str_slug($name).'.'.$extension;
                        $size = $request->file($key)->getSize() / 1024;
                        \Log::info('FILENAME: '.$filename);
                        $request->file($key)->move(public_path('storage/uploads'), $filename);
                        Media::create([
                            'model_type' => $model_type,
                            'model_id' => $model->id,
                            'name' => $filename,
                            'type' => $request->file($key)->getClientMimeType(),
                            'file_name' => $filename,
                            'size' => $size,
                        ]);
                    }
                    $finalRequest = $finalRequest = new Request($request->except($downloadable_file_input));
                    \Log::info(json_encode($finalRequest));
                }
            }
        }

        return $finalRequest;
    }


    public function saveAllFiles(Request $request, $downloadable_file_input = null, $model_type = null, $model = null, $single = false)
    {
        ini_set('memory_limit', '-1');
        if (!file_exists(public_path('storage/uploads'))) {
            mkdir(public_path('storage/uploads'), 0777);
            mkdir(public_path('storage/upload/thumb'), 0777);
        }
        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {

            if ($request->hasFile($key)) {
                if ($key == $downloadable_file_input && $downloadable_file_input != 'workshop_image') {
                    if($single){
                        if ($request->has($key . '_max_width') && $request->has($key . '_max_height')) {
                            // Check file width
                            $extension = array_last(explode('.',$request->file($key)->getClientOriginalName()));
                            $name = array_first(explode('.',$request->file($key)->getClientOriginalName()));
                            $filename = time() . '-' . str_slug($name).'.'.$extension;
                            $file = $request->file($key);
                            $size = $file->getSize() / 1024;
                            $image = Image::make($file);
                            if (!file_exists(public_path('storage/uploads/thumb'))) {
                                mkdir(public_path('storage/uploads/thumb'), 0777, true);
                            }

                            Image::make($file)->resize(50, 50)->save(public_path('storage/uploads/thumb') . '/' . $filename);

                            $width = $image->width();
                            $height = $image->height();
                            if ($width > $request->{$key . '_max_width'} && $height > $request->{$key . '_max_height'}) {
                                $image->resize($request->{$key . '_max_width'}, $request->{$key . '_max_height'});
                            } elseif ($width > $request->{$key . '_max_width'}) {
                                $image->resize($request->{$key . '_max_width'}, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                            } elseif ($height > $request->{$key . '_max_width'}) {
                                $image->resize(null, $request->{$key . '_max_height'}, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                            }
                            $image->save(public_path('storage/uploads') . '/' . $filename);
                            Media::create([
                                'model_type' => $model_type,
                                'model_id' => $model->id,
                                'name' => $filename,
                                'type' => $file->getClientMimeType(),
                                'file_name' => $filename,
                                'size' => $size,
                            ]);
                        } else {
                            $extension = array_last(explode('.',$request->file($key)->getClientOriginalName()));
                            $name = array_first(explode('.',$request->file($key)->getClientOriginalName()));
                            $filename = time() . '-' . str_slug($name).'.'.$extension;
                            $size = $request->file($key)->getSize() / 1024;
                            $request->file($key)->move(public_path('storage/uploads'), $filename);
                            Media::create([
                                'model_type' => $model_type,
                                'model_id' => $model->id,
                                'name' => $filename,
                                'type' => $request->file($key)->getClientMimeType(),
                                'file_name' => $filename,
                                'size' => $size,
                            ]);
                        }

                    }else {
                        foreach ($request->file($key) as $item) {
                            $extension = array_last(explode('.', $item->getClientOriginalName()));
                            $name = array_first(explode('.', $item->getClientOriginalName()));
                            $filename = time() . '-' . str_slug($name) . '.' . $extension;
                            $size = $item->getSize() / 1024;
                            $item->move(public_path('storage/uploads'), $filename);
                            Media::create([
                                'model_type' => $model_type,
                                'model_id' => $model->id,
                                'name' => $filename,
                                'type' => $item->getClientMimeType(),
                                'file_name' => $filename,
                                'size' => $size,
                            ]);
                        }
                    }
                    $finalRequest = $finalRequest = new Request($request->except($downloadable_file_input));


                } else if ($key == $downloadable_file_input && $downloadable_file_input == 'workshop_image') {
                    $images = '';
                    foreach ($request->file($key) as $item) {
                        $extension = array_last(explode('.',$item->getClientOriginalName()));
                        $name = array_first(explode('.',$item->getClientOriginalName()));
                        $filename = time() . '-' . str_slug($name).'.'.$extension;
                        $size = $item->getSize() / 1024;
                        $item->move(public_path('storage/uploads'), $filename);
                        Media::create([
                            'model_type' => $model_type,
                            'model_id' => $model->id,
                            'name' => $filename,
                            'type' => $item->getClientMimeType(),
                            'file_name' => $filename,
                            'size' => $size,
                        ]);

                        if($images != '')
                            $images .= ',';

                        $images .= $filename;
                    }
                    $finalRequest = new Request(array_merge($finalRequest->all(), ['images' => $images]));


                } else {
                    if($key != 'video_file'){
                        if($key == 'add_pdf'){
                            $file = $request->file($key);

                            $extension = array_last(explode('.',$request->file($key)->getClientOriginalName()));
                            $name = array_first(explode('.',$request->file($key)->getClientOriginalName()));
                            $filename = time() . '-' . str_slug($name).'.'.$extension;

                            $size = $file->getSize() / 1024;
                            $file->move(public_path('storage/uploads'), $filename);
                            Media::create([
                                'model_type' => $model_type,
                                'model_id' => $model->id,
                                'name' => $filename,
                                'type' => 'lesson_pdf',
                                'file_name' => $filename,
                                'size' => $size,
                            ]);
                            $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                        }elseif($key == 'add_audio'){
                            $file = $request->file($key);

                            $extension = array_last(explode('.',$request->file($key)->getClientOriginalName()));
                            $name = array_first(explode('.',$request->file($key)->getClientOriginalName()));
                            $filename = time() . '-' . str_slug($name).'.'.$extension;

                            $size = $file->getSize() / 1024;
                            $file->move(public_path('storage/uploads'), $filename);
                            Media::create([
                                'model_type' => $model_type,
                                'model_id' => $model->id,
                                'name' => $filename,
                                'type' => 'lesson_audio',
                                'file_name' => $filename,
                                'url' => asset('storage/uploads/'.$filename),
                                'size' => $size,
                            ]);
                            $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                        } else{
                            $extension = array_last(explode('.',$request->file($key)->getClientOriginalName()));
                            $name = array_first(explode('.',$request->file($key)->getClientOriginalName()));
                            $filename = time() . '-' . str_slug($name).'.'.$extension;

                            $request->file($key)->move(public_path('storage/uploads'), $filename);
                            $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                            $model->lesson_image = $filename;
                            $model->save();
                        }

                    }
                }
            }
        }

        return $finalRequest;
    }

    public function saveOneMedia($item, $model_type = null, $model = null)
    {
        ini_set('memory_limit', '-1');
        if (!file_exists(public_path('storage/uploads'))) {
            mkdir(public_path('storage/uploads'), 0777);
            mkdir(public_path('storage/upload/thumb'), 0777);
        }

        $extension = array_last(explode('.', $item->getClientOriginalName()));
        $name = array_first(explode('.', $item->getClientOriginalName()));
        $filename = time() . '-' . str_slug($name) . '.' . $extension;
        $size = $item->getSize() / 1024;
        $item->move(public_path('storage/uploads'), $filename);
        $media = Media::create([
            'model_type' => $model_type,
            'model_id' => $model->id,
            'name' => $filename,
            'type' => $item->getClientMimeType(),
            'file_name' => $filename,
            'size' => $size,
        ]);

        return $media;
    }

    public function saveLogos(Request $request){
        if (!file_exists(public_path('storage/logos'))) {
            mkdir(public_path('storage/logos'), 0777);
        }
        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                    $filename = time() . '-' . str_slug($request->file($key)->getClientOriginalName());
                    $request->file($key)->move(public_path('storage/logos'), $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));

            }
        }

        return $finalRequest;
    }
}