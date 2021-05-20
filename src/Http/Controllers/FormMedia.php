<?php

namespace Lake\FormMedia\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use LakeFormMedia;
use Lake\FormMedia\MediaManager;

class FormMedia extends Controller
{
    /**
     * 获取文件列表
     */
    public function getFiles()
    {
        $path = request()->input('path', '/');
        
        $currentPage = (int) request()->input('page', 1);
        $perPage = (int) request()->input('pageSize', 120);
        
        $manager = (new MediaManager())
            ->setPath($path);
            
        $type = (string) request()->input('type', 'image');
        $order = (string) request()->input('order', 'time');
        
        $files = $manager->ls($type, $order);
        $list = collect($files)
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();
            
        $totalPage = count(collect($files)->chunk($perPage));

        $data = [
            'list' => $list, // 数据
            'total_page' => $totalPage, // 数量
            'current_page' => $currentPage, // 当前页码
            'per_page' => $perPage, // 每页数量
            'nav' => $manager->navigation()  // 导航
        ];
        
        return $this->renderJson(LakeFormMedia::trans('form-media.get_success'), 200, $data);
    }

    /**
     * 上传
     */
    public function upload()
    {
        $files = request()->file('files');
        $path = request()->get('path', '/');
        
        $type = request()->get('type');
        $nametype = request()->get('nametype', 'uniqid');
        
        $manager = (new MediaManager())
            ->setPath($path)
            ->setNametype($nametype);
        
        if ($type != 'blend') {
            if (! $manager->checkType($files, $type)) {
                return $this->renderJson(LakeFormMedia::trans('form-media.upload_file_ext_error'), -1);
            }
        }
        
        try {
            $uploadResult = $manager->upload($files);

            if ($uploadResult['status']) {
                // 插入附件表
                foreach ($uploadResult['data'] as $item) {
                    DB::table('attachment')->insert([
                        'filename'   => $item['filename'] ,
                        'filesize'   => $item['filesize'] ,
                        'mime_type'  => $item['mime_type'] ,
                        'url'        => $item['url'] ,
                        'created_at' => $item['created_at'] ,
                        'updated_at' => $item['created_at'] ,
                    ]);
                }
                return $this->renderJson(LakeFormMedia::trans('form-media.upload_success'), 200, $uploadResult['data']);
            }
        } catch (\Exception $e) {
            return $this->renderJson(LakeFormMedia::trans('form-media.upload_error'), -1);
        }
        
        return $this->renderJson(LakeFormMedia::trans('form-media.upload_error'), -1);
    }

    /**
     * 新建文件夹
     */
    public function createFolder()
    {
        $dir = request()->input('dir');
        $name = request()->input('name');

        $manager = (new MediaManager())
            ->setPath($dir);

        try {
            if ($manager->createFolder($name)) {
                return $this->renderJson(LakeFormMedia::trans('form-media.create_success'), 200);
            }
        } catch (\Exception $e) {
            return $this->renderJson(LakeFormMedia::trans('form-media.create_error'), -1);
        }
        
        return $this->renderJson(LakeFormMedia::trans('form-media.create_error'), -1);
    }
    
    /**
     * 输出json
     */
    protected function renderJson($msg, $code = 200, $data = [])
    {
        return response()->json([
            'code' => $code, 
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}



