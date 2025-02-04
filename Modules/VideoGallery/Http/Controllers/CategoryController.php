<?php

namespace Modules\VideoGallery\Http\Controllers;

use Aws\S3\Exception\S3Exception as S3;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\VideoGallery\Entities\VideoCategory;
use Image;
use File;
use Modules\Language\Entities\Language;
use Validator;
use LaravelLocalization;

class CategoryController extends Controller
{
    public function categories()
    {
        // dd(78);
        $categories = VideoCategory::orderBy('id', 'ASC')->paginate(10);
        $activeLang = Language::where('status', 'active')->orderBy('name', 'ASC')->get();
        return view('videogallery::categories', compact('activeLang', 'categories'));
    }

    public function storeCategory(Request $request)
    {
        // dd($request->all());
        Validator::make($request->all(), [
            'name' => 'required|unique:video_categories|min:2|max:40',
        ])->validate();



        if ($request->hasFile('cover_image')) :

            $requestImage = $request->file('cover_image');

            $fileType = $requestImage->getClientOriginalExtension();
            $originalImageName = date('YmdHis') . "_galleryImage_big" . rand(1, 50) . '.' . $fileType;
            $thumbnailImageName = date('YmdHis') . "_galleryImage_thumb" . rand(1, 50) . '.' . $fileType;

            if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') == 's3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                $directory = 'images/';
            else :
                $directory = 'public/images/';
            endif;

            $originalImageUrl       = $directory . $originalImageName;
            $thumbnailImageUrl      = $directory . $thumbnailImageName;

            if (settingHelper('default_storage') == 's3') :

                //ogImage
                $imgOriginal    = Image::make($requestImage)->stream();
                $imgThumbnail   = Image::make($requestImage)->fit(100, 100)->stream();

                try {
                    Storage::disk('s3')->put($originalImageUrl, $imgOriginal);
                    Storage::disk('s3')->put($thumbnailImageUrl, $imgThumbnail);
                } catch (S3 $e) {
                    return redirect()->back()->with('error', __('something_went_wrong'));
                }
            elseif (settingHelper('default_storage') == 'local') :

                //                dd($requestImage);
                Image::make($requestImage)->save($originalImageUrl);
                Image::make($requestImage)->fit(100, 100)->save($thumbnailImageUrl);
            endif;

        endif;

        $category = new VideoCategory();

        $category->name        = $request->name;

        $category->save();

        return redirect()->back()->with('success', __('successfully_added'));
    }

    public function editCategory($id)
    {
        $category = VideoCategory::findOrfail($id);
        $activeLang = Language::where('status', 'active')->orderBy('name', 'ASC')->get();

        return view('videogallery::edit_category', compact('category', 'activeLang'));
    }

    public function updateCategory(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|min:2|max:40|unique:video_categories,name,' . $request->category_id,
        ])->validate();

        if ($request->hasFile('cover_image')) :

            $requestImage = $request->file('cover_image');

            $fileType = $requestImage->getClientOriginalExtension();
            $originalImageName = date('YmdHis') . "_galleryImage_big" . rand(1, 50) . '.' . $fileType;
            $thumbnailImageName = date('YmdHis') . "_galleryImage_thumb" . rand(1, 50) . '.' . $fileType;

            if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') == 's3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                $directory = 'images/';
            else :
                $directory = 'public/images/';
            endif;

            $originalImageUrl       = $directory . $originalImageName;
            $thumbnailImageUrl      = $directory . $thumbnailImageName;

            if (settingHelper('default_storage') == 's3') :

                //ogImage
                $imgOriginal    = Image::make($requestImage)->stream();
                $imgThumbnail   = Image::make($requestImage)->fit(100, 100)->stream();

                try {
                    Storage::disk('s3')->put($originalImageUrl, $imgOriginal);
                    Storage::disk('s3')->put($thumbnailImageUrl, $imgThumbnail);
                } catch (S3 $e) {
                    return redirect()->back()->with('error', __('something_went_wrong'));
                }
            elseif (settingHelper('default_storage') == 'local') :

                //                dd($requestImage);
                Image::make($requestImage)->save($originalImageUrl);
                Image::make($requestImage)->fit(100, 100)->save($thumbnailImageUrl);
            endif;

        endif;

        $category = VideoCategory::findOrfail($request->category_id);


        $category->name = $request->name;

        if ($request->hasFile('cover_image')) :
            $category->original_image   = str_replace("public/", "", $originalImageUrl);
            $category->thumbnail        = str_replace("public/", "", $thumbnailImageUrl);
            $category->disk = settingHelper('default_storage');
        endif;

        $category->save();

        return redirect()->route('video.categories')->with('success', __('successfully_updated'));
    }

    public function deleteCategory(Request $request)
    {
        $category = VideoCategory::find($request->cat_id);
        if ($category->videos->count()) {
            $data['status']     = "error";
            $data['message']    =  'ابتدا ویدئوهای موجود در این گروه را حذف نمائید';
            // dd(json_encode($data));

            echo json_encode($data);
        } else {
            $category->delete();
            $data['status']     = "success";
            $data['message']    =  __('successfully_deleted');

            echo json_encode($data);
        }
    }

    public function fetchAlbum(Request $request)
    {
        $select = $request->get('select');
        $value = $request->get('value');
        $data = Album::where('language', $value)->get();
        $output = '<option value="">' . __('select_album') . '</option>';
        foreach ($data as $row) :
            $output .= '<option value="' . $row->id . '">' . $row->name . '</option>';
        endforeach;

        echo $output;
    }

    public function fetchTabs(Request $request)
    {
        $select = $request->get('select');
        $value = $request->get('value');
        $data = Album::where('id', $value)->first();
        $output = '<option class="text-capitalize" value="">' . __('select_tab') . '</option>';

        foreach (explode(',', $data->tabs) as $tab) :
            $output .= '<option class="text-capitalize" value="' . $tab . '">' . $tab . '</option>';
        endforeach;

        echo $output;
    }

    //    public function addCategory(Request $request)
    //    {
    //        Validator::make($request->all(), [
    //            'name' => 'required|unique:image_categories|min:2|max:40',
    //            'slug' => 'nullable|min:2|unique:image_categories|max:30|regex:/^\S*$/u',
    //            'album_id' => 'required',
    //        ])->validate();
    //
    //        $category = new ImageCategory();
    //
    //        $category->name = $request->name;
    //        $category->album_id = $request->album_id;
    //
    //        if ($request->slug != null) :
    //            $category->slug = $request->slug;
    //        else :
    //            $category->slug = $this->make_slug($request->name);
    //        endif;
    //
    //        $category->save();
    //
    //        return redirect()->back()->with('success', __('successfully_added'));
    //    }
    //
    //    public function editCategory($id)
    //    {
    //        $imageCategory  = ImageCategory::findOrfail($id);
    //        $albums         = Album::all();
    //        $activeLang     = Language::where('status', 'active')->orderBy('name', 'ASC')->get();
    //
    //        return view('gallery::edit_category', compact('imageCategory', 'activeLang', 'albums'));
    //    }
    //
    //    public function updateCategory(Request $request)
    //    {
    //        Validator::make($request->all(), [
    //            'name'      => 'required|min:2|max:40|unique:image_categories,name,' . $request->category_id,
    //            'slug'      => 'nullable|min:2|max:30|regex:/^\S*$/u|unique:image_categories,slug,' . $request->category_id,
    //            'album_id'  => 'required'
    //        ])->validate();
    //
    //        $imageCategory = ImageCategory::findOrfail($request->category_id);
    //
    //        $imageCategory->name = $request->name;
    //        $imageCategory->album_id = $request->album_id;
    //
    //        if ($request->slug != null) :
    //            $imageCategory->slug = $request->slug;
    //        else :
    //            $imageCategory->slug = $this->make_slug($request->name);
    //        endif;
    //
    //        $imageCategory->save();
    //
    //        return redirect()->route('album-categories')->with('success', __('successfully_updated'));
    //    }
    //
    //    public function fetchCategory(Request $request)
    //    {
    //        $select = $request->get('select');
    //        $value = $request->get('value');
    //        $data = ImageCategory::where('album_id', $value)->get();
    //        $output = '<option value="">' . __('select_category') . '</option>';
    //        foreach ($data as $row) :
    //            $output .= '<option value="' . $row->id . '">' . $row->name . '</option>';
    //        endforeach;
    //
    //        echo $output;
    //    }

    public function addImage()
    {
        $activeLang = Language::where('status', 'active')->orderBy('name', 'ASC')->get();
        $albums = Album::where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))->get();
        return view('gallery::add_gallery_image', compact('activeLang', 'albums'));
    }

    public function saveImageGallery(Request $request)
    {
        //        dd( $request->all());

        Validator::make($request->all(), [
            'album_id' => 'required',
            'files' => 'required',
        ])->validate();

        if ($request->hasFile('files')) :
            try {

                $images = $request->file('files');

                foreach ($images as $requestImage) :

                    $galleryImage = new GalleryImage();

                    $fileType = $requestImage->getClientOriginalExtension();
                    $originalImageName = date('YmdHis') . "_galleryImage_big" . rand(1, 50) . '.' . $fileType;
                    $thumbnailImageName = date('YmdHis') . "_galleryImage_thumb" . rand(1, 50) . '.' . $fileType;

                    if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') == 's3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                        $directory = 'images/';
                    else :
                        $directory = 'public/images/';
                    endif;

                    $originalImageUrl       = $directory . $originalImageName;
                    $thumbnailImageUrl      = $directory . $thumbnailImageName;

                    if (settingHelper('default_storage') == 's3') :

                        //ogImage
                        $imgOriginal    = Image::make($requestImage)->stream();
                        $imgThumbnail   = Image::make($requestImage)->fit(100, 100)->stream();

                        try {
                            Storage::disk('s3')->put($originalImageUrl, $imgOriginal);
                            Storage::disk('s3')->put($thumbnailImageUrl, $imgThumbnail);
                        } catch (S3 $e) {
                            return redirect()->back()->with('error', __('something_went_wrong'));
                        }
                    elseif (settingHelper('default_storage') == 'local') :

                        //                dd($requestImage);
                        Image::make($requestImage)->save($originalImageUrl);
                        Image::make($requestImage)->fit(100, 100)->save($thumbnailImageUrl);
                    endif;

                    $galleryImage->original_image   = str_replace("public/", "", $originalImageUrl);
                    $galleryImage->thumbnail        = str_replace("public/", "", $thumbnailImageUrl);

                    $galleryImage->album_id = $request->album_id;
                    $galleryImage->tab = $request->tab;
                    $galleryImage->title = $request->title;
                    $galleryImage->disk = settingHelper('default_storage');
                    $galleryImage->save();

                endforeach;

                return redirect()->back()->with('success', __('successfully_added'));
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return null;
            }
        else :
            return redirect()->back()->with('error', __('something_went_wrong'));
        endif;
    }

    public function editImage($id)
    {
        $galleryImage       = GalleryImage::findOrfail($id);
        $activeLang         = Language::where('status', 'active')->orderBy('name', 'ASC')->get();
        $albums             = Album::where('language', @$galleryImage->album->language)->get();
        //        $image_categories   = ImageCategory::where('album_id', $galleryImage->album_id)->get();

        return view('gallery::edit_gallery_image', compact('activeLang', 'albums', 'galleryImage'));
    }

    public function updateImage(Request $request)
    {
        //        dd($request->all());
        Validator::make($request->all(), [
            'album_id'  => 'required',
            'image'     => 'mimes:jpg,JPG,JPEG,jpeg,png|max:5120',
        ])->validate();

        $requestImage = $request->file('image');

        $galleryImage = GalleryImage::findOrfail($request->galleryImage_id);
        //        dd();

        if (isset($requestImage)) :

            if ($galleryImage->disk == 's3') :
                if (Storage::disk('s3')->exists($galleryImage->original_image) && !blank($galleryImage->original_image)) :
                    Storage::disk('s3')->delete($galleryImage->original_image);
                endif;
                if (Storage::disk('s3')->exists($galleryImage->thumbnail) && !blank($galleryImage->thumbnail)) :
                    Storage::disk('s3')->delete($galleryImage->thumbnail);
                endif;
            elseif ($galleryImage->disk == 'local') :
                if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                    $path = '';
                } else {
                    $path = 'public/';
                }

                if (File::exists($path . $galleryImage->original_image) && !blank($galleryImage->original_image)) :
                    unlink($path . $galleryImage->original_image);
                endif;
                if (File::exists($path . $galleryImage->thumbnail) && !blank($galleryImage->thumbnail)) :
                    unlink($path . $galleryImage->thumbnail);
                endif;
            endif;

            $fileType = $requestImage->getClientOriginalExtension();

            $originalImageName = date('YmdHis') . "_galleryImage_big" . rand(1, 50) . '.' . $fileType;
            $thumbnailImageName = date('YmdHis') . "_galleryImage_thumb" . rand(1, 50) . '.' . $fileType;

            if (strpos(php_sapi_name(), 'cli') !== false || settingHelper('default_storage') == 's3' || defined('LARAVEL_START_FROM_PUBLIC')) :
                $directory = 'images/';
            else :
                $directory = 'public/images/';
            endif;

            $originalImageUrl = $directory . $originalImageName;
            $thumbnailImageUrl = $directory . $thumbnailImageName;

            if (settingHelper('default_storage') == 's3') :

                $imgOriginal = Image::make($requestImage)->stream();
                $imgThumbnail = Image::make($requestImage)->fit(100, 100)->stream();

                try {
                    Storage::disk('s3')->put($originalImageUrl, $imgOriginal);
                    Storage::disk('s3')->put($thumbnailImageUrl, $imgThumbnail);
                } catch (S3 $e) {
                    return redirect()->back()->with('error', __('something_went_wrong'));
                }

            elseif (settingHelper('default_storage') == 'local') :

                Image::make($requestImage)->save($originalImageUrl);
                Image::make($requestImage)->fit(100, 100)->save($thumbnailImageUrl);

            endif;

            $galleryImage->original_image   = str_replace("public/", "", $originalImageUrl);
            $galleryImage->thumbnail        = str_replace("public/", "", $thumbnailImageUrl);

            $galleryImage->disk             = settingHelper('default_storage');
        endif;
        $galleryImage->tab                  = $request->tab;
        $galleryImage->title                = $request->title;

        if ($galleryImage->album_id != $request->album_id && $galleryImage->is_cover) :
            $galleryImage->is_cover = 0;
        endif;

        $galleryImage->album_id             = $request->album_id;

        $galleryImage->save();

        return redirect()->back()->with('success', __('successfully_updated'));
    }

    public function setCover(Request $request)
    {
        $galleryImage       = GalleryImage::findOrfail($request->image_id);

        $old_cover          = GalleryImage::where('album_id', $galleryImage->album_id)->where('is_cover', 1)->first();

        if (isset($old_cover)) :
            $old_cover->is_cover = 0;
            $old_cover->save();
        endif;

        $galleryImage->is_cover     = 1;
        $galleryImage->save();

        $data['status']     = "success";
        $data['message']    =  __('successfully_updated');

        echo json_encode($data);
    }

    public function filterImage(Request $request)
    {
        $activeLang         = Language::where('status', 'active')->orderBy('name', 'ASC')->get();
        $search_query       = $request;

        $albums         = Album::where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))->get();
        //        dd($albums);

        $galleryImages = GalleryImage::where('album_id', 'like', '%' . $request->album_id . '%')
            ->where('tab', 'like', '%' . $request->tab . '%')
            ->where('title', 'like', '%' . $request->search_key . '%')
            ->orderBy('id', 'desc')
            ->paginate('15');
        return view('gallery::image_search', compact('albums', 'activeLang', 'search_query', 'galleryImages'));
    }

    private function make_slug($string, $delimiter = '-')
    {

        $string = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $string);

        $string = preg_replace("/[\/_|+ -]+/", $delimiter, $string);
        $result = mb_strtolower($string);

        if ($result) :
            return $result;
        else :
            return $string;
        endif;
    }
}
