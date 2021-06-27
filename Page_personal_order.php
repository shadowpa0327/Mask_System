<?php
session_start();
if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="annoymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="homepage.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <title>DB HW2</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.1/jquery.twbsPagination.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <title>DB HW2</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light navbar-laravel" style = "border-bottom : 2px solid rgba(235, 231, 231, 0.842); background-color:rgb(236, 236, 236);">
            <div class="container" id = "top-navigation" style = "margin-bottom:0px;" >
                <a class="navbar-brand" href="#"><h3>NMSL口罩訂購網</h3></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home_page.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Check_exist_shop.php">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">LogOut</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
        <script>
            var jsArray;
            var update_mode = "";
            var search_condition = "all";
            var current_page = 1;
            var per_page;
            var formData_for_search;
            $.fn.serializeObject = function(){
                var obj = {};
                var delete_idx = [];
                console.log("-------where error detected--------");
                console.log(jsArray);
                //console.log(this.serializeArray());
                $.each( this.serializeArray(), function(i,o){
                var n = o.name,
                    v = o.value,
                    tmp_v = o.value;
                    console.log(n,v);
                    if(n==="multiselect[]"){
                        console.log(v);
                        jsArray[v]['status'] = update_mode;
                        jsArray[v]['EndTime'] = "<?php
                            $dateTime = new DateTime;
                            echo($dateTime->format('Y-m-d H:i:s'));
                        ?>";
                        jsArray[v]['Finish_user'] = "<?php
                                echo($_SESSION['Username']);
                            ?>";
                        if(search_condition!="all" &&update_mode!=search_condition &&  jsArray != undefined ){
                            //console.log(jsArray);
                            //jsArray.splice(v,1);
                            delete_idx.push(v);
                        }
                        v = jsArray[v]['OID'];
                        
                    } 
                    console.log("---finish---");
                    obj[n] = obj[n] === undefined ? v
                    : $.isArray( obj[n] ) ? obj[n].concat( v )
                    : [ obj[n], v ];
                });
                for(let i =delete_idx.length-1;i>=0;i--){
                    jsArray.splice(delete_idx[i],1);
                }
                obj['update_mode'] = update_mode;
                console.log('object',obj);
                return obj;
            };
            function update_table(){
                console.log("trigger_update_table");
                $('#show tr').each(function(){
                   if($(this).children().eq(2).text()==="Cancelled"||$(this).children().eq(2).text()==="Finished"){
                       console.log('mother',$(this).children().eq(7).html());
                       $(this).children().eq(7).empty() ;
                       $(this).children().eq(0).empty() ;
                   }
                })
            }
            function fetch_searching_result(){
                console.log(formData_for_search);
                $.ajax({
                            type: "POST",
                            url: "search_order.php",
                            data:formData_for_search , // serializes the form's elements.
                            success: function(data)
                            {  
                                current_page = 1;
                                console.log(data);
                                jsArray= JSON.parse(data);
                                console.log(jsArray);
                                generate_pagination();
                                update_table();
                            }
                        });
            }
            function generate_pagination(){
                update_mode = "";
                let data_length = jsArray.length; 
                let per_page = 5;
                let total = Math.ceil(data_length/5);
                //console.log(total);
                $('#idChk-xs').prop('checked',false);
                $('#multi_button_cancel').hide();
                $('#show tbody').empty();
                if(!total){
                    $('#show').html("<span id = \"span_error_msg\ class=\"text-danger\">cannot found the matching result</span>");
                }
                else{
                    $('#show').html(`<thead>
                                        <tr>
                                            <td></td>
                                            <td >OID</td><td>status</td>
                                            <td>Start</td><td>End</td>
                                            <td>Shop</td> <td>Total Price</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>`);
                }
                
                if($('.pagination').data("twbs-pagination")){
                    $('.pagination').twbsPagination('destroy');
                }
                if(total==1){
                    //console.log("trigger");
                    $('#show').append("<tbody>");
                    for(let cnt = 0;cnt < data_length ; cnt++ ){
                        //console.log('cnt',cnt);
                        $('#show').append(`<tr>
                                    <td><input type="checkbox" class="order_check_box"  id="checkbox${cnt}" name="multiselect[]" value =${cnt}  style="display:none"></input></td>
                                    <td>${jsArray[cnt]['OID']}</td>
                                    <td>${jsArray[cnt]['status']}</td>
                                    <td>${jsArray[cnt]['StartTime']}
                                        <br>
                                        ${jsArray[cnt]['Create_user']}
                                    </td>
                                    <td>${((jsArray[cnt]['EndTime']==null)?"":(jsArray[cnt]['EndTime']+"<br>"))}
                                        ${((jsArray[cnt]['EndTime']==null)?"":(jsArray[cnt]['Finish_user']))}
                                    </td>
                                    <td>${jsArray[cnt]['StoreName']}</td>
                                    <td>${parseInt(jsArray[cnt]['Order_Number'])*parseInt(jsArray[cnt]['Order_Price'])}<br>
                                        (${parseInt(jsArray[cnt]['Order_Number'])}*$${parseInt(jsArray[cnt]['Order_Price'])})</td>
                                    <td>
                                        <button type = "submit" value="Cancel" class="btn btn-danger btn-sm submit-button" name="${cnt}">X</button></td>
                                    </tr>`);
                    }
                    $('#show').hide();
                    $('#show').fadeIn();
                    $('#show').append("</tbody>");
                    update_table();
                }
                else if(total!=0){
                    window.pagObj = $('#pagination').twbsPagination({
                    // totalPages如果妳一頁最多顯示2筆資料,那總長度就是除2 所以有5個分頁
                    totalPages: total,
                    startPage : current_page,
                    visiblePages: 5,
                    onPageClick: function (event, page) {
                        console.log(`current:${page}`);
                        current_page = page;
                        //console.info(page + ' (from options)');
                        //console.log("totz",data_length/2+1);
                        // 所以第1頁顯示mycars的1,2  2頁->3,4  3頁->5,6
                        // text()顯示妳的資料
                        // 在text()當中可以適當穿插css
                        var tableHeaderRowCount = 1;
                        var table = document.getElementById('show');
                        var rowCount = table.rows.length;
                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        $('#show').append("<tbody>");
                        for(let cnt = (page-1)*per_page , idx = 0;  idx<per_page && cnt < data_length ; idx++,cnt++ ){
                            //console.log('cnt',cnt,' ',jsArray[cnt]['EndTime']);
                            //console.log(cnt,' ',jsArray[cnt]['Create_user']);
                            $('#show').append(`<tr>
                                    <td><input type="checkbox" class="order_check_box"  id="checkbox${cnt}" name="multiselect[]" value =${cnt}  style="display:none"></input></td>
                                    <td>${jsArray[cnt]['OID']}</td>
                                    <td>${jsArray[cnt]['status']}</td>
                                    <td>${jsArray[cnt]['StartTime']}
                                        <br>
                                        ${jsArray[cnt]['Create_user']}
                                    </td>
                                    <td>${((jsArray[cnt]['EndTime']==null)?"":(jsArray[cnt]['EndTime']+"<br>"))}
                                        ${((jsArray[cnt]['EndTime']==null)?"":(jsArray[cnt]['Finish_user']))}
                                    </td>
                                    <td>${jsArray[cnt]['StoreName']}</td>
                                    <td>${parseInt(jsArray[cnt]['Order_Number'])*parseInt(jsArray[cnt]['Order_Price'])}<br>
                                        (${parseInt(jsArray[cnt]['Order_Number'])}*$${parseInt(jsArray[cnt]['Order_Price'])})</td>
                                    <td>
                                        <button type = "submit" value="Cancel" class="btn btn-danger btn-sm submit-button" name="${cnt}">X</button></td>
                                    </tr>`);
                        }
                        $('#show').append("</tbody>");
                        $('#show').hide();
                        $('#show').fadeIn();
                        update_table();
                        }
                        }).on('page', function (event, page) {
                            console.info(page + ' (from event listening)');
                        });
                }
                
            }
            $(document).ready(function() {
                   
                    $('form.login').on('submit', function(e) {
                        e.preventDefault();
                        formData_for_search = $(this).serializeObject();
                        //console.log(formData);
                        $.ajax({
                            type: "POST",
                            url: "search_order.php",
                            data:formData_for_search , // serializes the form's elements.
                            success: function(data)
                            {  
                                //console.log(fromData_for_search);
                                current_page = 1;
                                //console.log(data);
                                jsArray= JSON.parse(data);
                                //console.log(jsArray);
                                generate_pagination();
                                update_table();
                            }
                        });
                    });
                    $('form.order').on('submit', function(e) {
                        e.preventDefault();
                        var formData = $(this).serializeObject();
                        console.log(formData);
                        $.ajax({
                            type: "POST",
                            url: "Update_order.php",
                            data: formData, // serializes the form's elements.
                            success: function(data)
                            {  
                                var tmp_arr = JSON.parse(data);
                                if(tmp_arr['msg']==update_mode){
                                    //alert(update_mode+" successfully!");
                                    $('#myModal .modal-body').html(`<p>${update_mode+" successfully!"}</p>`);
                                    $('#myModal').modal('show');
                                    generate_pagination();
                                    update_table();
                                }
                                else{
                                    //console.log("hello!");
                                    //alert(tmp_arr['msg']);
                                    $('#myModal .modal-body').html(`<p>${tmp_arr['msg']}</p>`);
                                    $('#myModal').modal('show');
                                    fetch_searching_result();
                                }
                            }
                        });
                    });
                    $(document).on('click','.submit-button',function(e){
                        //console.log($(this).attr('name'));
                        if($(this).attr('value')=="Cancel")update_mode = "Cancelled";
                        $(`#checkbox${$(this).attr('name')}`).prop('checked',true);
                    })
                    $("#idChk-xs").on('click',function(e){
                        //console.log($(this).prop('checked'));
                        
                    })
                    
                    $("#idChk-xs:checkbox").change(function(e){
                        if($(this).prop('checked')){
                            //console.log(1);
                            $('#show tr').each(function(){
                                $(this).children().eq(7).hide() ;
                            })
                            $(".order_check_box").fadeIn();
                        }
                        else{
                            //console.log(2);
                            $('#show tr').each(function(){
                                $(this).children().eq(7).fadeIn() ;
                            })
                            $(".order_check_box").fadeOut();
                        }
                    })
                    $("#order_status_select").change(function(e){
                        search_condition = $(this).children("option:selected").val();
                        console.log("Oh my mom ! I found the status change",search_condition);
                    })
                    
                    
                });
        </script>
        
        <div class="container" style = " margin-top:40px">
            <div class="card bg-light mb-6" style="max-width:48rem;">
                <div class="card-header"><h4>Searching</h4></div>
                <div class="card-body">
                    <form class="login" onsubmit="">
                    
                        <div class="form-group row">
                            <label for="amount" class="col-sm-2 col-form-label">Status</label>
                            <select name="amount" id="order_status_select">
                                    <option value="all">All</option>
                                    <option value="Not Finished">Not Finished</option>
                                    <option value="Finished">Finished</option>
                                    <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <input type="submit" value = "Search" class="btn btn-primary btn-xs pull-right"></input>
                    </form>
                </div>
            </div>
        </div>

        <div class="container " id = "show_result" >
            <div class="card bg-light mb-6" style="max-width:60rem;" >
            <div class="card-header"><h4>Result</h4></div>
                    
                    <form  method="post" class="order">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="switch switch-xs">
                                    <input type="checkbox" class="switch" id="idChk-xs" name = "show_working" value="show">
                                    <label for="idChk-xs">multiselect</label>
                                </span>
                            </div>
                            <div>
                                <input type = "submit" value="Cancel" class="btn btn-danger btn-sm submit-button  order_check_box" style="display:none" name="multi_cancel" id="multi_button_cancel"></input>
                            </div>
                        </div>
                    </div>
                    <div class="container" style="overflow-x:scroll; padding:0;margin:0">
                            <table class="table table-hover custab" id = "show" >
                            </table>
                        </div>
                    </form>
                    <div class = "col align-self-center card-body" style="padding:0px">
                        <nav aria-label="Page navigation" id = "page_number" >
                            <ul class="pagination justify-content-center" id="pagination"></ul>
                        </nav>
                    </div>
                
            </div>
        </div>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">System notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

    </body>
</html>