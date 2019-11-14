@extends('admin.layouts')
@section('css')
    <link href="/assets/global/vendor/bootstrap-table/bootstrap-table.min.css" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">用户封禁记录</h3>
            </div>
            <div class="panel-body">
                <div class="form-row">
                    <div class="form-group col-lg-3 col-sm-6">
                        <input type="text" class="form-control" name="username" id="username" value="{{Request::get('username')}}" placeholder="用户名"/>
                    </div>
                    <div class="form-group col-lg-2 col-sm-6 btn-group">
                        <button class="btn btn-primary" onclick="Search()">搜索</button>
                        <a href="/admin/userBanLogList" class="btn btn-danger">重置</a>
                    </div>
                </div>
                <table class="text-md-center" data-toggle="table" data-mobile-responsive="true">
                    <thead class="thead-default">
                    <tr>
                        <th> #</th>
                        <th> 用户名</th>
                        <th> 时长</th>
                        <th> 理由</th>
                        <th> 封禁时间</th>
                        <th> 最后连接时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($list->isEmpty())
                        <tr>
                            <td colspan="6">暂无数据</td>
                        </tr>
                    @else
                        @foreach($list as $vo)
                            <tr>
                                <td>
                                    <a href="/admin/userList?username={{$vo->id}}" target="_blank" rel="noopener"> {{$vo->id}}</a>
                                </td>
                                <td> {{empty($vo->user) ? '【账号已删除】' : $vo->user->username}} </td>
                                <td> {{$vo->minutes}}分钟</td>
                                <td> {{$vo->desc}} </td>
                                <td> {{$vo->created_at}} </td>
                                <td> {{date("Y-m-d H:i:s", $vo->user->t)}} </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-4">
                        共 <code>{{$list->total()}}</code> 条记录
                    </div>
                    <div class="col-sm-8">
                        <nav class="Page navigation float-right">
                            {{$list->links()}}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="/assets/global/vendor/bootstrap-table/bootstrap-table.min.js" type="text/javascript"></script>
    <script src="/assets/global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        //回车检测
        $(document).on("keypress", "input", function (e) {
            if (e.which === 13) {
                Search()
            }
        });

        // 搜索
        function Search() {
            const username = $("#username").val();
            window.location.href = '/admin/userBanLogList?username=' + username;
        }
    </script>
@endsection
