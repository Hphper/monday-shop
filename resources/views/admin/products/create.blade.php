@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/lib/layui/css/layui.css') }}">
    <style>
        .attr_container {
            margin: 10px auto;
        }
        .attr_container:after {clear:both;content:'1111';display:block;width: 0;height: 0;visibility:hidden;}
    </style>
@endsection

@section('main')
<div class="page-container">
    @if (session()->has('status'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ url('admin/products') }}" method="post" class="form form-horizontal" id="form-article-add">
        {{ csrf_field() }}

        <div class="row cl {{ $errors->has('name') ? 'has-error' : '' }}">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>商品名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" placeholder="商品名称" id="" name="name">
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row cl {{ $errors->has('category_id') ? 'has-error' : '' }}">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>商品分类：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box">
                    <select name="category_id" class="select" style="padding-bottom: 5px">
                                <option value="">请选择分类</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{!!  str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category->depth)  !!}{{ $category->ancestors->count() ? '┣━━' : '' }} {{ $category->name }}</option>
                        @endforeach
                    </select>
				</span>
                @if ($errors->has('category_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('category_id') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="row cl {{ ($errors->has('attribute.*') || $errors->has('items.*') || $errors->has('markup.*')) ? 'has-error' : '' }}" id="attrContainer">
            <div class="attr_container">
                <label class="form-label col-xs-4 col-sm-2">添加产品属性：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <button type="button" class="layui-btn" id="addAttrBtn">添加产品属性</button>
                    @if ($errors->has('attribute.*'))
                        <span class="help-block">
                        <strong>{{ $errors->first('attribute.*') }}</strong>
                    </span>
                    @endif
                    @if ($errors->has('items.*'))
                        <span class="help-block">
                        <strong>{{ $errors->first('items.*') }}</strong>
                    </span>
                    @endif
                    @if ($errors->has('markup.*'))
                        <span class="help-block">
                        <strong>{{ $errors->first('markup.*') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="attr_container">
                <label class="form-label col-xs-4 col-sm-2">产品属性：</label>
                <div class="formControls col-xs-8 col-sm-9" >
                    <input type="text" name="attribute[]" id="" placeholder="产品属性名：如颜色" value="" class="input-text" style=" width:25%">
                    ===>
                    <input type="text" name="items[]" id="" placeholder="产品属性值：对应颜色：红" value="" class="input-text" style=" width:25%">
                    ===>
                    <input type="text" name="markup[]" id="" placeholder="浮动价格，如白色的比较贵10￥" value="" class="input-text" style=" width:25%">
                </div>
            </div>
        </div>

        <div class="row cl {{ $errors->has('price') ? 'has-error' : '' }}">
            <label class="form-label col-xs-4 col-sm-2">销售价格：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="price" id="" placeholder="" value="" class="input-text" style="width:90%">
                元
                @if ($errors->has('price'))
                    <span class="help-block">
                        <strong>{{ $errors->first('price') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="row cl {{ $errors->has('price_original') ? 'has-error' : '' }}">
            <label class="form-label col-xs-4 col-sm-2">商品展示价格：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="price_original" id="" placeholder="" value="" class="input-text" style="width:90%">
                元
                @if ($errors->has('price_original'))
                    <span class="help-block">
                        <strong>{{ $errors->first('price_original') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="row cl {{ $errors->has('count') ? 'has-error' : '' }}">
            <label class="form-label col-xs-4 col-sm-2">库存量：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="count" id="" placeholder="" value="" class="input-text" style="width:90%">
                @if ($errors->has('count'))
                    <span class="help-block">
                        <strong>{{ $errors->first('count') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="row cl {{ $errors->has('unit') ? 'has-error' : '' }}">
            <label class="form-label col-xs-4 col-sm-2">价格计算单位：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" name="unit" id="" placeholder="如 件 / 个 / 台" value="" class="input-text" style="width:90%">
                @if ($errors->has('unit'))
                    <span class="help-block">
                        <strong>{{ $errors->first('unit') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="row cl {{ $errors->has('description') ? 'has-error' : '' }}">
            <label class="form-label col-xs-4 col-sm-2">商品描述：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <textarea name="description" id="description" style="display: none;"></textarea>
                @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                </span>
                @endif
            </div>

        </div>
        <div class="row cl {{ $errors->has('image') ? 'has-error' : '' }}">
            <label class="form-label col-xs-4 col-sm-2">图片上传：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <div class="uploader-list-container">
                    <div class="layui-upload">
                        <button title="第一张默认为商品缩略图" type="button" class="layui-btn" id="testList">选择商品图片</button>
                        <button type="button" class="layui-btn" id="testListAction">开始上传</button>
                        @if ($errors->has('image'))
                            <span class="help-block">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                        @endif
                        <div class="layui-upload-list">
                            <table class="layui-table">
                                <thead>
                                <tr><th>文件名</th>
                                    <th>大小</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr></thead>
                                <tbody id="demoList"></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="hidden_images_container">

        </div>

        <hr>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <input type="submit" class="layui-btn layui-btn-normal" value="添加商品">
            </div>
        </div>
    </form>
</div>

<!--请在下方写此页面业务相关的脚本-->
<script src="{{ asset('assets/admin/lib/layui/layui.js') }}"></script>
<script type="text/javascript">
    layui.use(['upload', 'layedit'], function() {
        var $ = layui.jquery
            , upload = layui.upload
            , layedit = layui.layedit;

        // 添加产品属性
        $("#addAttrBtn").click(function(){
            var inputText = '<div class="attr_container"><label class="form-label col-xs-4 col-sm-2 ">产品属性：</label><div class="formControls col-xs-8 col-sm-9"> <input type="text" name="attribute[]" id="" placeholder="产品属性名：如颜色" value="" class="input-text" style=" width:25%"> ===> <input type="text" name="items[]" id="" placeholder="产品属性值：对应颜色：红" value="" class="input-text" style=" width:25%"> ===> <input type="text" name="markup[]" id="" placeholder="浮动价格，如白色的比较贵10￥" value="" class="input-text" style=" width:25%"></div></div>';
            $('#attrContainer').append(inputText);
        });

        // 富文本编辑器
        layedit.set({
            uploadImage: {
                url: "{{ url('api/product/upload/product') }}?fieldName=file"
            }
        });
        layedit.build('description');

        //多文件列表示例
        var demoListView = $('#demoList')
            ,uploadListIns = upload.render({
            elem: '#testList'
            ,url: "{{ url('api/product/upload/images') }}"
            ,data: '{"_token":"{{ csrf_token() }}"}'
            ,accept: 'images'
            ,field: 'product_image'
            ,size: 1024*2
            ,multiple: true
            ,auto: false
            ,bindAction: '#testListAction'
            ,choose: function(obj){

                var files = obj.pushFile(); //将每次选择的文件追加到文件队列
                //读取本地文件
                obj.preview(function(index, file, result){
                    var tr = $(['<tr id="upload-'+ index +'">'
                        ,'<td>'+ file.name +'</td>'
                        ,'<td>'+ (file.size/1014).toFixed(1) +'kb</td>'
                        ,'<td>等待上传</td>'
                        ,'<td>'
                        ,'<button class="layui-btn layui-btn-mini demo-reload layui-hide">重传</button>'
                        ,'<button class="layui-btn layui-btn-mini layui-btn-danger demo-delete">删除</button>'
                        ,'</td>'
                        ,'</tr>'].join(''));

                    //单个重传
                    tr.find('.demo-reload').on('click', function(){
                        obj.upload(index, file);
                    });

                    //删除
                    tr.find('.demo-delete').on('click', function(){
                        delete files[index]; //删除对应的文件
                        tr.remove();
                    });

                    demoListView.append(tr);
                });
            }
            ,done: function(res, index, upload){

                if(res.code == 0){ //上传成功
                    var tr = demoListView.find('tr#upload-'+ index)
                        ,tds = tr.children();
                    tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                    tds.eq(3).html('<img src="/storage/'+ res.data.src +'" />'); //清空操作
                    // delete files[index]; //删除文件队列已经上传成功的文件

                    // 加入隐藏域
                    var text = "<input type='hidden' name='image[]' value='"+  res.data.src +"' />";
                    $('#hidden_images_container').append(text);

                    return;
                }

                layer.msg(res.msg);
                this.error(index, upload);
            }
            ,error: function(index, upload){
                var tr = demoListView.find('tr#upload-'+ index)
                    ,tds = tr.children();
                tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
            }
        });

    });
</script>
@endsection