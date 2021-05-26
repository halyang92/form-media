# Dcat-admin 表单媒体拓展


## 预览

### 表单
![form](https://user-images.githubusercontent.com/24578855/105875109-5aa30400-6038-11eb-9b5c-1c833e0c6b92.jpg)

### 动态表单
![form-array](https://user-images.githubusercontent.com/24578855/100456207-f24caa80-30fa-11eb-86a3-a8e3d2102655.jpg)

### 弹出选择框
![form-modal](https://user-images.githubusercontent.com/24578855/104125985-1277b680-5395-11eb-835b-c20e7c7585f9.jpg)


## 环境
 - PHP >= 7.2.5
 - Dcat-admin ^2.0


## 安装

### composer 安装

```
composer require halyang92/form-media
```

### 安装扩展

在 `开发工具->扩展` 安装本扩展


## 使用

#### 单图 数据库结构 varchar

##### 可删除

```
$form->photo('photo','图片')
    ->nametype('datetime')
    ->remove(true)
    ->help('单图，可删除');
```

##### 不可删除

```
$form->photo('photo','图片')
    ->path('pic') 
    ->nametype('uniqid') 
    ->remove(false)
    ->help('单图，不可删除');

$form->photo('photo','图片')
    ->nametype('uniqid') 
    ->help('单图，不可删除');
```

#### 多图 数据库结构 json

```
$form->photos('photo', '图片')
    ->path('pic') 
    ->pageSize(16)
    ->nametype('uniqid') 
    ->limit(9)
    ->remove(true);  //可删除
```

#### 视频 数据库结构 json/varchar

```
$form->video('video','视频')
    ->path('video') 
    ->nametype('uniqid') 
    ->remove(true);  //可删除
```

### 参数说明
```
path(string)    ： 快速定位目录，默认为根目录
nametype(string)： 文件重命名方式 uniqid|datetime，默认 uniqid
pageSize(string)： 弹出层列表每页显示数量
limit(int)      ： 限制条数
remove(boolean) :  是否有删除按钮
textField(boolean) : 是否开启文本字段 ，默认关闭

photo 、 photos 、 video  的 参数默认值不一样

photo  默认 limit = 1  remove = false

photos 默认 limit = 9  remove = true

video  默认 limit = 1  remove = true
```

##### 多图上传提交的数据为 json 字符串，如需输出数组，请在对应模型中加入下面代码
```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demo extends Model
{
    
    public function getPicturesAttribute($pictures)
    {

        return json_decode($pictures, true);

    }

}
```

## 特别鸣谢

感谢 `detail` 提供的原始代码
```
https://github.com/deatil/dcat-form-media
```
