<link rel="stylesheet" href="assets/admin/css/compiled/user-list.css" type="text/css" media="screen" />
        <!-- main container -->
        <div class="content">
            <div class="container-fluid">
                <div id="pad-wrapper" class="users-list">
                    <div class="row-fluid header">
                        <h3>分类列表</h3>
                        <div class="span10 pull-right">
                            <a href="/index.php?r=admin%2Fcategory%2Fadd" class="btn-flat success pull-right">
                                <span>&#43;</span>添加新分类</a></div>
                    </div>
                    <!-- Users table -->
                    <div class="row-fluid table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="span3 sortable">
                                        <span class="line"></span>分类ID</th>
                                    <th class="span3 sortable">
                                        <span class="line"></span>分类名称</th>
                                    <th class="span3 sortable align-right">
                                        <span class="line"></span>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- row -->
                                <tr class="first">
                                    <td>1</td>
                                    <td>|-----服装</td>
                                    <td class="align-right">
                                        <a href="/index.php?r=admin%2Fcategory%2Fmod&cateid=1">编辑</a>
                                        <a href="/index.php?r=admin%2Fcategory%2Fdel&cateid=1">删除</a></td>
                                </tr>
                                <tr class="first">
                                    <td>2</td>
                                    <td>|-----|-----上衣</td>
                                    <td class="align-right">
                                        <a href="/index.php?r=admin%2Fcategory%2Fmod&cateid=2">编辑</a>
                                        <a href="/index.php?r=admin%2Fcategory%2Fdel&cateid=2">删除</a></td>
                                </tr>
                                <tr class="first">
                                    <td>3</td>
                                    <td>|-----电子产品</td>
                                    <td class="align-right">
                                        <a href="/index.php?r=admin%2Fcategory%2Fmod&cateid=3">编辑</a>
                                        <a href="/index.php?r=admin%2Fcategory%2Fdel&cateid=3">删除</a></td>
                                </tr>
                                <tr class="first">
                                    <td>6</td>
                                    <td>|-----|-----手机</td>
                                    <td class="align-right">
                                        <a href="/index.php?r=admin%2Fcategory%2Fmod&cateid=6">编辑</a>
                                        <a href="/index.php?r=admin%2Fcategory%2Fdel&cateid=6">删除</a></td>
                                </tr>
                                <tr class="first">
                                    <td>4</td>
                                    <td>|-----充气娃娃</td>
                                    <td class="align-right">
                                        <a href="/index.php?r=admin%2Fcategory%2Fmod&cateid=4">编辑</a>
                                        <a href="/index.php?r=admin%2Fcategory%2Fdel&cateid=4">删除</a></td>
                                </tr>
                                <tr class="first">
                                    <td>5</td>
                                    <td>|-----|-----仓也空井也空</td>
                                    <td class="align-right">
                                        <a href="/index.php?r=admin%2Fcategory%2Fmod&cateid=5">编辑</a>
                                        <a href="/index.php?r=admin%2Fcategory%2Fdel&cateid=5">删除</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pull-right"></div>
                    <!-- end users table --></div>
            </div>
        </div>
        <!-- end main container -->