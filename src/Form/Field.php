<?php

namespace Lake\FormMedia\Form;

use Illuminate\Support\Facades\Storage;

use Dcat\Admin\Form\Field as BaseField;

use Lake\FormMedia\MediaManager;

/**
 * 表单字段
 *
 * @create 2020-11-25
 * @author deatil
 */
class Field extends BaseField
{
    protected $view = 'lake-form-media::field';
 
    protected static $css = [
        '@extension/halyang92/form-media/field.css'
    ];

    protected static $js = [
        '@extension/halyang92/form-media/jquery.dragsort.js',
        '@extension/halyang92/form-media/field.js'
    ];
    
    protected $uploadUrl = '';
    protected $listUrl = '';
    protected $createFolderUrl = '';
    protected $type = '';
    
    protected $disableUpload = false;
    protected $disableCreateFolder = false;

    protected $path = '';
    protected $limit = 1;
    protected $remove = false;
    
    protected $nametype = 'uniqid';
    protected $pageSize = 120;

    protected $textField = false;

    protected $action = '';

    /**
     * 设置上传链接
     *
     * @param string $uploadUrl
     *
     * @return $this
     */
    public function uploadUrl($uploadUrl = null)
    {
        $this->uploadUrl = $uploadUrl;

        return $this;
    }

    /**
     * 设置数据列表链接
     *
     * @param string $listUrl
     *
     * @return $this
     */
    public function listUrl($listUrl = null)
    {
        $this->listUrl = $listUrl;

        return $this;
    }

    /**
     * 设置新建文件夹链接
     *
     * @param string $createFolderUrl
     *
     * @return $this
     */
    public function createFolderUrl($createFolderUrl = null)
    {
        $this->createFolderUrl = $createFolderUrl;

        return $this;
    }

    /**
     * 禁止上传
     *
     * @return $this
     */
    public function disableUpload()
    {
        $this->disableUpload = true;

        return $this;
    }

    /**
     * 禁止创建文件夹
     *
     * @return $this
     */
    public function disableCreateFolder()
    {
        $this->disableCreateFolder = true;

        return $this;
    }

    /**
     * 设置类型
     *
     * 类型包括：blend、image、xls、word、ppt、pdf、code、zip、text、audio、video
     * 其中 blend 为全部类型
     *
     * @param string $type
     *
     * @return $this
     */
    public function type($type = 'image')
    {
        $this->type = $type;

        return $this;
    }

    /**
     * 设置当前可用目录
     *
     * @param string $path
     *
     * @return $this
     */
    public function path($path = '')
    {
        $this->path = $path;
        
        return $this;
    }

    /**
     * 设置限制数量.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit = 1)
    {
        if ($limit == 0) {
            return $this;
        }
        $this->limit = $limit;

        return $this;
    }

    /**
     * 移除
     *
     * @param boolen $remove
     *
     * @return $this
     */
    public function remove($remove = false)
    {
        $this->remove = $remove;
        
        return $this;
    }

    /**
     * 设置上传保存文件名类型
     *
     * @param string $type uniqid|datetime
     *
     * @return $this
     */
    public function nametype($type = 'uniqid')
    {
        if ($type == 'datetime') {
            $type = 'datetime';
        } else {
            $type = 'uniqid';
        }
        
        $this->nametype = $type;
        return $this;
    }

    /**
     * 设置每页数量
     *
     * @param int $pageSize
     *
     * @return $this
     */
    public function pageSize($pageSize = 120)
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * 文本框字段
     *
     * @param boolen $textField
     * @return $this
     */
    public function textField($textField = false)
    {
        $this->textField = $textField;

        return $this;
    }

    /**
     * 设置操作位置，new新建，edit编辑
     *
     * @param string $action
     * @return $this
     */
    public function action($action = 'new')
    {
        $this->action = $action;

        return $this;
    }

    public function render()
    {
        $path = $this->path;
        $limit = $this->limit;
        $type = $this->type;
        $nametype = $this->nametype;
        $pageSize = $this->pageSize;
        $rootpath = (new MediaManager())->buildUrl('');
        $remove = ($this->remove == true) ? 1 : 0;
        $textField = ($this->textField == true) ? 1 : 0;
        $method = $this->action;
        
        if (empty($this->uploadUrl)) {
            $this->uploadUrl = admin_route('admin.lake-form-media.upload');
        }
        
        if (empty($this->listUrl)) {
            $this->listUrl = admin_route('admin.lake-form-media.get-files');
        }
        
        if (empty($this->createFolderUrl)) {
            $this->createFolderUrl = admin_route('admin.lake-form-media.create-folder');
        }
        
        if ($this->disableUpload) {
            $this->uploadUrl = '';
        }
        if ($this->disableCreateFolder) {
            $this->createFolderUrl = '';
        }

        $this->addVariables([
            'options' => [
                'path' => $path,
                'limit' => $limit,
                'type' => $type,
                'nametype' => $nametype,
                'pageSize' => $pageSize,
                'rootpath' => $rootpath,
                'remove' => $remove,
                'textField' => $textField,
                
                'get_files_url' => $this->listUrl,
                'upload_url' => $this->uploadUrl,
                'create_folder_url' => $this->createFolderUrl,

                'action'    => $this->action
            ],
        ]);

        return parent::render();
    }

}
