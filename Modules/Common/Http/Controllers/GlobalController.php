<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\Api\Entities\AppIntro;
use Modules\Gallery\Entities\GalleryImage;
use Modules\Post\Entities\Post;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Validator;
use Sentinel;
use DB;
use File;
use Modules\User\Entities\Permission;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Modules\Appearance\Entities\Menu;
use Modules\Appearance\Entities\MenuItem;
use Modules\Page\Entities\Page;
use Modules\Widget\Entities\Widget;
use Modules\Social\Entities\SocialMedia;

class GlobalController extends Controller
{
    public function switchLanguage($code)
    {
        $lang   =  $code;
        App::setLocale($lang);
        Session::put('locale', $lang);
        LaravelLocalization::setLocale($lang);
        $url    = \LaravelLocalization::getLocalizedURL(App::getLocale(), \URL::previous());

        Cache::forget('activeLang');

        return Redirect::to($url);
    }

    public function postDelete(Request $request)
    {
        $tablename      = $request->table_name;
        $id             = $request->row_id;
        if ($tablename == 'users') :
            $query = User::find($id);

            if ($query->count() > 0) :
                $query->delete();
                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;

        elseif ($tablename == 'roles') :
            if ($id > 0 && $id < 5) :
                $data['status']     = "error";
                $data['message']    = __('you_can_not_delete_this');
            else :
                $role               = Role::find($id);
                $roleForAttach      = Role::find(3);

                $users              = Role::find($id)->withUsers()->get();

                if ($users->count() > 0) :
                    foreach ($users as $user) :
                        $oldRole    = DB::table('role_users')->where('user_id', '=', $user->id);
                        //need to detect first
                        if (!empty($oldRole)) :
                            $oldRole->delete();
                        endif;
                        $roleForAttach->users()->attach($user);
                    endforeach;
                endif;

                $role->delete();
                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            endif;

        elseif ($tablename == 'widgets') :
            $widget                 = Widget::findOrFail($id);
            if ($widget->is_custom == 0) :
                $data['status']     = "error";
                $data['message']    = __('you_can_not_delete_this');
            else :
                $widget->delete();
                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            endif;


        elseif ($tablename == 'pages') :
            $query = Page::find($id);
            if ($query->count() > 0) :
                $q = DB::table('menu_item')->where('page_id', $id);
                if ($q->count()) {
                    $data['status']     = "error";
                    $data['message']    = 'ابتدا منوهای زیرمجموعه این صفحه را حذف نمائید';
                } else {
                    $query->delete();
                    $data['status']     = "success";
                    $data['message']    = __('successfully_deleted');
                }
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;


        elseif ($tablename == 'menu') :
            $query                  = MenuItem::where('menu_id', $id)->get();
            if ($query->count() > 0) :
                $query->each->delete();
                $data['url']        = route('menu-item');
                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;

        elseif ($tablename == 'social_media') :
            $query = SocialMedia::find($id);
            if ($query->count() > 0) :
                $query->delete();
                $data['url']        = route('socials');
                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;

        elseif ($tablename == 'menu_item') :
            $query                  = MenuItem::find($id);
            $query1                 = MenuItem::where('parent', $id);

            if ($query->count() > 0) :
                $query1->delete();
                $query->delete();
                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;
        elseif ($tablename == 'posts') :
            $q = DB::table('menu_item')->where('post_id', $id);
            if ($q->count()) {
                $data['status']     = "error";
                $data['message']    = 'ابتدا منوهای زیرمجموعه این خبر را حذف نمائید';
            } else {
                DB::table('category_post')->where('post_id', $id)->delete();
                DB::table('post_sub_category')->where('post_id', $id)->delete();
                DB::table('post_state')->where('post_id', $id)->delete();
                DB::table('post_section')->where('post_id', $id)->delete();
                $query      = DB::table($tablename)->where('id', $id);
                $posts      = Post::findOrfail($id);
                $post_type = $posts->post_type;
                if ($query->count() > 0) :
                    $query->delete();
                    // if ($post_type == 'audio') :
                    //     $posts->audio()->detach();
                    // endif;
                    $data['status']     = "success";
                    $data['message']    = __('successfully_deleted');
                else :
                    $data['status']     = "error";
                    $data['message']    = __('not_found');
                endif;
            }
        elseif ($tablename == 'albums') :
            $query          = DB::table($tablename)->where('id', $id);
            $galleryImages  = GalleryImage::where('album_id', $id)->get();


            if ($query->count() > 0) :

                foreach ($galleryImages as $galleryImage) :
                    $galleryImage->tab = '';
                    $galleryImage->is_cover = 0;
                    $galleryImage->save();
                endforeach;

                $query->delete();

                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;
        elseif ($tablename == 'gallery_images') :
            $query          = DB::table($tablename)->where('id', $id);
            $galleryImage   = GalleryImage::findOrfail($id);
            if ($query->count() > 0) :
                if ($galleryImage->disk == 's3') :
                    if (Storage::disk('s3')->exists($galleryImage->original_image) && !blank($galleryImage->original_image)) :
                        Storage::disk('s3')->delete($galleryImage->original_image);
                    endif;
                    if (Storage::disk('s3')->exists($galleryImage->thumbnail) && !blank($galleryImage->thumbnail)) :
                        Storage::disk('s3')->delete($galleryImage->thumbnail);
                    endif;

                    $query->delete();

                    $data['status']     = "success";
                    $data['message']    = __('successfully_deleted');

                elseif ($galleryImage->disk == 'local') :
                    //public path
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
                    $query->delete();

                    $data['status']     = "success";
                    $data['message']    = __('successfully_deleted');
                else :
                    $data['status']     = "error";
                    $data['message']    = __('not_found');
                endif;
            else :
                $query = DB::table($tablename)->where('id', $id);
                if ($query->count() > 0) :
                    $query->delete();
                    $data['status']     = "success";
                    $data['message']    = __('successfully_deleted');
                else :
                    $data['status']     = "error";
                    $data['message']    = __('not_found');
                endif;
            endif;

        elseif ($tablename == 'app_intros') :
            $query          = DB::table($tablename)->where('id', $id);
            $app_intro   = AppIntro::findOrfail($id);
            if ($query->count() > 0) :
                if ($app_intro->disk == 's3') :
                    if (Storage::disk('s3')->exists($app_intro->image) && !blank($app_intro->image)) :
                        Storage::disk('s3')->delete($app_intro->image);
                    endif;

                    $query->delete();

                    $data['status']     = "success";
                    $data['message']    = __('successfully_deleted');

                elseif ($app_intro->disk == 'local') :
                    //public path
                    if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
                        $path = '';
                    } else {
                        $path = 'public/';
                    }

                    if (File::exists($path . $app_intro->image) && !blank($app_intro->image)) :
                        unlink($path . $app_intro->image);
                    endif;
                    $query->delete();

                    $data['status']     = "success";
                    $data['message']    = __('successfully_deleted');
                else :
                    $data['status']     = "error";
                    $data['message']    = __('not_found');
                endif;

            else :
                $query = DB::table($tablename)->where('id', $id);
                if ($query->count() > 0) :
                    $query->delete();
                    $data['status']     = "success";
                    $data['message']    = __('successfully_deleted');
                else :
                    $data['status']     = "error";
                    $data['message']    = __('not_found');
                endif;
            endif;

        elseif ($tablename == 'categories') :

            // added by majid molaea for check that no relation record exist in subcategories table
            $query = DB::table('sub_categories')->where('category_id', $id);
            // added by majid molaea for check that no relation record exist in menu_item table
            $query2 = DB::table('menu_item')->where('category_id', $id);

            if ($query->count() > 0) :
                $data['status']     = "error";
                $data['message']    = "ابتدا زیر گروه های این گروه را حذف نمائید";
            elseif ($query2->count() > 0) :
                $data['status']     = "error";
                $data['message']    = "ابتدا منوهای زیرمجموعه این گروه را حذف نمائید";
            else :
                DB::table('categories')->where('id', $id)->delete();
                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            endif;
        elseif ($tablename == 'sub_categories') :
            $query = DB::table('menu_item')->where('sub_category_id', $id);
            if ($query->count() > 0) :
                $data['status']     = "error";
                $data['message']    = "ابتدا منوهای زیرمجموعه این زیرگروه را حذف نمائید";
            else :
                DB::table('sub_categories')->where('id', $id)->delete();
                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            endif;
        elseif ($tablename == 'side_ads') :
            $ad =  DB::table($tablename)->where('id', $id)->first();
            if ($ad->path) {
                @unlink($ad->path);
            }
            DB::table($tablename)->where('id', $id)->delete();
            $data['status']     = "success";
            $data['message']    = __('successfully_deleted');

        elseif ($tablename == 'center_ads') :
            $ad =  DB::table($tablename)->where('id', $id)->first();
            if ($ad->path) {
                @unlink($ad->path);
            }
            DB::table($tablename)->where('id', $id)->delete();
            $data['status']     = "success";
            $data['message']    = __('successfully_deleted');

        else :
            $query = DB::table($tablename)->where('id', $id);
            if ($query->count() > 0) :
                $query->delete();
                $data['status']     = "success";
                $data['message']    = __('successfully_deleted');
            else :
                $data['status']     = "error";
                $data['message']    = __('not_found');
            endif;

        endif;

        Cache::Flush();

        echo json_encode($data);
    }

    public function editInfo($page_name, $param1 = null)
    {
        $otherLinks = null;

        if ($param1) :
            $otherLinks = explode('/', $param1);
        endif;

        $page_name = $page_name;

        return view("common::modal.$page_name", [
            'param' => $otherLinks
        ]);
    }

    public function selectImage($media_id, $tableName, $model_id)
    {
        // $user=User::find($model_id);

        $user = DB::table($tableName)->where('id', $model_id)->update(['avatar_id' => $media_id]);

        return redirect()->back();
    }
}
